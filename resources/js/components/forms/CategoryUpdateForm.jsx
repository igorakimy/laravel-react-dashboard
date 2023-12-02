import { Divider, Form, Input, Modal, Select, Typography, Upload } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import TextArea from "antd/es/input/TextArea";
import { LoadingOutlined, PlusOutlined } from "@ant-design/icons";

const CategoryUpdateForm = ({
  open,
  category,
  onUpdate,
  onCancel,
  errors,
  onError,
}) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(false);
  const [imageUrl, setImageUrl] = useState("");
  const [imagePath, setImagePath] = useState("");
  const [fileList, setFileList] = useState([]);
  const [previewOpen, setPreviewOpen] = useState(false);
  const [previewImage, setPreviewImage] = useState("");
  const [previewTitle, setPreviewTitle] = useState("");

  const { Title } = Typography;

  useEffect(() => {
    form.setFieldsValue(category);
    form.setFieldValue("parent", category.parent?.id);
    // form.setFieldValue("file", category.image?.url);
    setImageUrl("");
    setFileList(
      category.image
        ? [
            {
              name: category.image.file_name,
              type: category.image.mime_type,
              size: category.image.size,
              url: category.image.url,
            },
          ]
        : [],
    );
    setLoading(false);
    getCategories();
  }, [category]);

  const getCategories = () => {
    axiosClient
      .get("/categories?kind=select")
      .then(({ data }) => {
        setCategories(
          data
            .map((item) => {
              return {
                label: item.name,
                value: item.id,
              };
            })
            .filter((item) => item.value !== category.id),
        );
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const sendForm = () => {
    form
      .validateFields()
      .then((values) => {
        form.resetFields();
        onUpdate(category?.id, values);
        onError();
      })
      .catch((err) => {});
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    form.setFieldsValue({
      [name]: value,
      errors: [],
    });
  };

  const normFile = (e) => {
    if (Array.isArray(e)) {
      return e;
    }
    return e?.fileList;
  };

  const getBase64 = (file) =>
    new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result);
      reader.onerror = (error) => reject(error);
    });

  const handleCancel = () => setPreviewOpen(false);

  const handlePreview = async (file) => {
    if (!file.url && !file.preview) {
      file.preview = await getBase64(file.originFileObj);
    }
    setPreviewImage(file.url || file.preview);
    setPreviewOpen(true);
    setPreviewTitle(
      file.name || file.url.substring(file.url.lastIndexOf("/") + 1),
    );
  };

  const uploadImage = ({ file, onSuccess, onError, onProgress }) => {
    let formData = new FormData();
    formData.append("file", file);
    axiosClient
      .post("/temporary/upload", formData, {
        onUploadProgress: (event) => {
          let percent = Math.floor((event.loaded / event.total) * 100);
          onProgress({ percent });
        },
      })
      .then(({ data }) => {
        console.log(data);
        onSuccess({ url: data?.url, path: data?.path });
      })
      .catch((error) => {
        console.log(error);
        onError(error);
      });
  };

  const categoryImageOnChange = (info) => {
    setFileList(info.fileList);

    if (info.file.status === "uploading") {
      setLoading(true);
      return;
    }

    if (info.file.status === "done") {
      const url = info.file.response.url;
      const path = info.file.response.path;
      setImageUrl(url);
      setImagePath(path);
      form.setFieldValue("image", path);
      setLoading(false);
    }
  };

  const uploadButton = (
    <div>
      {loading ? <LoadingOutlined /> : <PlusOutlined />}
      <div
        style={{
          marginTop: 8,
        }}
      >
        Add Image
      </div>
    </div>
  );

  return (
    <Modal
      maskClosable={false}
      destroyOnClose={true}
      open={open}
      okText="Update"
      cancelText="Cancel"
      onCancel={onCancel}
      onOk={sendForm}
      style={{
        top: 20,
      }}
    >
      <Title level={4}>Edit Category</Title>
      <Divider style={{ margin: "0.6rem 0" }}></Divider>
      <Form
        labelCol={{ span: 8 }}
        wrapperCol={{ span: 16 }}
        form={form}
        initialValues={category}
        labelAlign="left"
        onFieldsChange={() =>
          setClientReady(
            !form.isFieldsTouched(true) ||
              form.getFieldsError().some((field) => field.errors.length > 0),
          )
        }
      >
        <Form.Item name="image" hidden={true}>
          <Input />
        </Form.Item>
        <Form.Item
          label="Image"
          valuePropName="file"
          getValueFromEvent={normFile}
          style={{
            marginBottom: 16,
          }}
        >
          <Upload
            action="/api/temporary/upload"
            listType="picture-card"
            fileList={fileList}
            customRequest={uploadImage}
            onChange={categoryImageOnChange}
            onPreview={handlePreview}
          >
            {fileList.length === 1 ? null : uploadButton}
          </Upload>
          <Modal
            open={previewOpen}
            title={previewTitle}
            footer={null}
            onCancel={handleCancel}
          >
            <img
              alt="example"
              style={{
                width: "100%",
              }}
              src={previewImage}
            />
          </Modal>
        </Form.Item>

        <Form.Item
          name="name"
          label="Name"
          rules={[
            {
              required: true,
              message: "Please input the name of category",
            },
          ]}
          validateStatus={errors.name ? "error" : null}
          help={errors.name ? errors.name[0] : null}
        >
          <Input onChange={handleInputChange} />
        </Form.Item>
        <Form.Item
          name="parent"
          label="Parent Category"
          rules={[]}
          validateStatus={errors.parent ? "error" : null}
          help={errors.parent ? errors.parent[0] : null}
        >
          <Select
            placeholder="Parent Category"
            allowClear
            options={categories}
          />
        </Form.Item>
        <Form.Item
          name="description"
          label="Description"
          rules={[]}
          validateStatus={errors.description ? "error" : null}
          help={errors.description ? errors.description[0] : null}
        >
          <TextArea onChange={handleInputChange} />
        </Form.Item>
      </Form>
    </Modal>
  );
};

export default CategoryUpdateForm;
