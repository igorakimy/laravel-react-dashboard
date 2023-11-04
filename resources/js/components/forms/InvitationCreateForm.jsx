import { Divider, Form, Input, Modal, Select, Typography } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import TextArea from "antd/es/input/TextArea";

const InvitationCreateForm = ({
  open,
  onCreate,
  onCancel,
  errors,
  onError,
}) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [roles, setRoles] = useState([]);

  const { Title } = Typography;

  useEffect(() => {
    getRoles();
  }, []);

  const getRoles = () => {
    axiosClient
      .get("/roles?kind=select")
      .then(({ data }) => {
        setRoles(
          data.map((item) => {
            return {
              label: item.name,
              value: item.id,
            };
          }),
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
        onCreate(values);
        onError();
        if (!form.getFieldsError()) {
          form.resetFields();
        }
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

  return (
    <Modal
      maskClosable={false}
      open={open}
      okText="Send"
      okButtonProps={{
        style: {
          backgroundColor: "#218340",
        },
      }}
      cancelText="Cancel"
      onCancel={onCancel}
      onOk={sendForm}
      style={{
        top: 20,
      }}
    >
      <Title level={4}>New Invitation</Title>
      <Divider style={{ margin: "0.6rem 0" }}></Divider>
      <Form
        labelCol={{ span: 8 }}
        wrapperCol={{ span: 16 }}
        form={form}
        labelAlign="left"
        onFieldsChange={() =>
          setClientReady(
            !form.isFieldsTouched(true) ||
              form.getFieldsError().some((field) => field.errors.length > 0),
          )
        }
      >
        <Form.Item
          name="email"
          label="Email"
          rules={[
            {
              required: true,
              message: "Please input the email for invitation!",
            },
            {
              type: "email",
              message: "The input is not valid email!",
            },
          ]}
          validateStatus={errors.email ? "error" : null}
          help={errors.email ? errors.email[0] : null}
        >
          <Input onChange={handleInputChange} />
        </Form.Item>
        <Form.Item
          name="roles"
          label="Allowed Roles"
          rules={[
            {
              required: true,
              message: "Please select allowed roles for invites user!",
              type: "array",
            },
          ]}
          validateStatus={errors.roles ? "error" : null}
          help={errors.roles ? errors.roles[0] : null}
        >
          <Select allowClear mode="multiple" options={roles} />
        </Form.Item>

        <Form.Item
          name="message_text"
          label="Message"
          rules={[]}
          validateStatus={errors.message_text ? "error" : null}
          help={errors.message_text ? errors.message_text[0] : null}
        >
          <TextArea onChange={handleInputChange} />
        </Form.Item>
      </Form>
    </Modal>
  );
};

export default InvitationCreateForm;
