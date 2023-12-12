import {
  Button,
  Divider,
  Form,
  message,
  Modal,
  Radio,
  Select,
  Space,
  Steps,
  Typography,
  Upload,
} from "antd";
import {useEffect, useState} from "react";
import {UploadOutlined} from "@ant-design/icons";
import axiosClient from "../../axios-client.js";
import MapImportFieldsTable from "../tables/MapImportFieldsTable.jsx";

const ImportProductModal = ({open, loading, updateProducts, onImport, onCancel, errors}) => {
  const [mappingForm] = Form.useForm();
  const [importForm] = Form.useForm();
  const [current, setCurrent] = useState(0);
  const [configureContent, setConfigureContent] = useState("");
  const [mapFieldsContent, setMapFieldsContent] = useState("");
  const [previewContent, setPreviewContent] = useState("");
  const [fileList, setFileList] = useState([]);
  const [filePath, setFilePath] = useState("");
  const [uploading, setUploading] = useState(false);
  const [mappingDataSource, setMappingDataSource] = useState([]);
  const [localFields, setLocalFields] = useState([]);
  const [importHeaders, setImportHeaders] = useState([]);

  const {Title} = Typography;

  const initValues = {
    duplicates: "skip",
    delimiter: ",",
  };

  const getLocalFields = () => {
    axiosClient
      .get("/local-fields")
      .then(({data}) => {
        setLocalFields(data);
      })
      .catch((err) => console.log(err));
  };

  const getImportHeaders = (filepath) => {
    axiosClient
      .get("/imports/headers?filepath=" + filepath)
      .then(({data}) => {
        setImportHeaders(data.headers);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const uploadFile = () => {
    const formData = new FormData();
    fileList.forEach((file) => {
      formData.append("file", file);
    });
    setUploading(true);
    axiosClient
      .post("/temporary/upload", formData)
      .then(({data}) => {
        //setFileList([]);
        setFilePath(data.path);
        message.success("upload successfully.");
        getImportHeaders(data.path);
        mappingForm.resetFields();
      })
      .catch((err) => {
        message.error("upload failed.");
      })
      .finally(() => {
        setUploading(false);
      });

    // let formData = new FormData();
    // formData.append("file", file);
    // axiosClient
    //   .post("/temporary/upload", formData, {
    //     onUploadProgress: (event) => {
    //       let percent = Math.floor((event.loaded / event.total) * 100);
    //       onProgress({percent});
    //     },
    //   })
    //   .then(({data}) => {
    //     console.log(data);
    //     onSuccess({url: data?.url, path: data?.path});
    //   })
    //   .catch((error) => {
    //     console.log(error);
    //     onError(error);
    //   });
  };

  const importFileOnChange = (info) => {
    setFileList(info.fileList);
  };

  const handleBeforeUpload = (file) => {
    setFileList([file]);
    return false;
  };

  const handleFileRemove = (file) => {
    const index = fileList.indexOf(file);
    const newFileList = fileList.slice();
    newFileList.splice(index, 1);
    setFileList(newFileList);
  };

  const getConfigureContent = () => {
    return (
      <Form
        style={{
          marginTop: 32,
        }}
        labelCol={{span: 8}}
        wrapperCol={{span: 16}}
        form={importForm}
        labelAlign="left"
        initialValues={initValues}
      >
        <Form.Item
          name="file"
          label="Import File"
          rules={[
            {
              required: true,
              message: "Please, select import file",
            },
          ]}
          validateStatus={errors.file ? "error" : null}
          help={errors.file ? errors.file[0] : null}
        >
          <Upload
            name="file"
            // customRequest={uploadFile}
            // onChange={importFileOnChange}
            onRemove={handleFileRemove}
            beforeUpload={handleBeforeUpload}
            fileList={fileList}
            maxCount={1}
          >
            <Button icon={<UploadOutlined/>}>Click to Upload CSV file</Button>
          </Upload>
        </Form.Item>

        <Form.Item
          name="duplicates"
          label="Duplicates"
          rules={[
            {
              required: true,
              message: "Please, specify behaviour with duplicate products",
            },
          ]}
          validateStatus={errors.duplicates ? "error" : null}
          help={errors.duplicates ? errors.duplicates[0] : null}
        >
          <Radio.Group>
            <Space direction="vertical">
              <Radio value="skip">Skip</Radio>
              <Radio value="overwrite">Overwrite</Radio>
            </Space>
          </Radio.Group>
        </Form.Item>

        <Form.Item
          name="delimiter"
          label="File Delimiter"
          rules={[]}
          validateStatus={errors.delimiter ? "error" : null}
          help={errors.delimiter ? errors.delimiter[0] : null}
        >
          <Select
            options={[
              {
                label: "Comma ( , )",
                value: ",",
              },
              {
                label: "Semicolon ( ; )",
                value: ";",
              },
            ]}
          />
        </Form.Item>
      </Form>
    );
  };

  const handleImport = () => {
    mappingForm
      .validateFields()
      .then((values) => {
        axiosClient
          .post('/products/import', {filepath: filePath, ...values})
          .then(({data}) => {
            onCancel();
            updateProducts();
          })
          .catch((err) => console.log(err))
      })
      .catch((err) => console.log(err));
  };

  const steps = [
    {
      title: "Configure",
      content: getConfigureContent(),
    },
    {
      title: "Map Fields",
      content: <MapImportFieldsTable mappingForm={mappingForm} fields={localFields} headers={importHeaders}/>,
    },
  ];

  const next = () => {
    if (current === 0) {
      uploadFile();
      getLocalFields();
    }

    setCurrent(current + 1);
  };

  const prev = () => {
    setCurrent(current - 1);
  };

  const items = steps.map((item) => ({
    key: item.title,
    title: item.title,
  }));

  return (
    <Modal
      destroyOnClose={true}
      maskClosable={false}
      open={open}
      // okText="Import"
      // cancelText="Cancel"
      onCancel={onCancel}
      // onOk={sendForm}
      style={{
        top: 20,
      }}
      footer={(_, {OkBtn, CancelBtn}) => (
        <>
          {current > 0 && (
            <Button
              style={{
                margin: "0 0 0 8px",
              }}
              onClick={() => prev()}
            >
              Previous
            </Button>
          )}
          {current < steps.length - 1 && (
            <Button
              disabled={fileList.length === 0}
              type="primary"
              onClick={() => next()}
            >
              Next
            </Button>
          )}
          {current === steps.length - 1 && (
            <Button type="primary" onClick={() => handleImport()}>
              Import
            </Button>
          )}
          <Button onClick={() => onCancel()}>Cancel</Button>
        </>
      )}
    >
      <Title level={4}>Import Products</Title>
      <Divider style={{margin: "0.6rem 0"}}></Divider>
      <Steps current={current} items={items}/>
      <div>{steps[current].content}</div>
      <div
        style={{
          marginTop: 24,
        }}
      ></div>
    </Modal>
  );
};

export default ImportProductModal;
