import { Divider, Form, Modal, Radio, Select, Space, Typography } from "antd";

const ExportProductModal = ({ open, loading, onExport, onCancel, errors }) => {
  const [exportForm] = Form.useForm();

  const { Title } = Typography;

  const sendForm = () => {
    exportForm
      .validateFields()
      .then((values) => {
        onExport(values);
      })
      .catch((err) => console.log(err));
  };

  const handleFormatChange = () => {};

  const initValues = {
    decimal_format: ".",
    export_as: "csv",
  };

  return (
    <Modal
      destroyOnClose={true}
      maskClosable={false}
      open={open}
      okText="Export"
      okButtonProps={{
        loading: loading,
      }}
      cancelText="Cancel"
      onCancel={onCancel}
      onOk={sendForm}
      style={{
        top: 20,
      }}
    >
      <Title level={4}>Export Products</Title>
      <Divider style={{ margin: "0.6rem 0" }}></Divider>
      <Form
        labelCol={{ span: 8 }}
        wrapperCol={{ span: 16 }}
        form={exportForm}
        labelAlign="left"
        initialValues={initValues}
      >
        <Form.Item
          name="decimal_format"
          label="Decimal Format"
          rules={[
            {
              required: true,
              message: "Decimal Format must be specified",
            },
          ]}
          validateStatus={errors.decimal_format ? "error" : null}
          help={errors.decimal_format ? errors.decimal_format[0] : null}
        >
          <Select
            options={[
              {
                value: ".",
                label: "1234567.89",
              },
              {
                value: ",",
                label: "1234567,89",
              },
            ]}
          />
        </Form.Item>

        <Form.Item
          name="export_as"
          label="Export As"
          rules={[
            {
              required: true,
              message: "You must specify export format",
            },
          ]}
          validateStatus={errors.decimal_format ? "error" : null}
          help={errors.decimal_format ? errors.decimal_format[0] : null}
        >
          <Radio.Group onChange={handleFormatChange}>
            <Space direction="vertical">
              <Radio value="csv">CSV (Comma Separated Value)</Radio>
              <Radio value="xlsx">XLSX (Microsoft Excel)</Radio>
            </Space>
          </Radio.Group>
        </Form.Item>
      </Form>
    </Modal>
  );
};

export default ExportProductModal;
