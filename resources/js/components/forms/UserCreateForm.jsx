import { Divider, Form, Input, Modal, Typography } from "antd";
import { useState } from "react";

const UserCreateForm = ({ open, onCreate, onCancel, errors, onError }) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);

  const { Title } = Typography;

  const sendForm = () => {
    form
      .validateFields()
      .then((values) => {
        onCreate(values);
        onError();

        console.log(errors, Object.keys(errors).length);
        // form.resetFields();
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
      okText="Create"
      okButtonProps={{
        disabled: clientReady,
      }}
      cancelText="Cancel"
      onCancel={onCancel}
      onOk={sendForm}
      style={{
        top: 20
      }}
    >
      <Title level={4}>New User</Title>
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
          name="name"
          label="Name"
          rules={[
            {
              required: true,
              message: "Please input the name of user!",
            },
          ]}
          validateStatus={errors.name ? "error" : null}
          help={errors.name ? errors.name[0] : null}
        >
          <Input onChange={handleInputChange} />
        </Form.Item>
        <Form.Item
          name="email"
          label="Email"
          rules={[
            {
              required: true,
              message: "Please input the email of user!",
            },
            {
              type: "email",
              message: "The input is not valid E-mail!",
            },
          ]}
          validateStatus={errors.email ? "error" : null}
          help={errors.email ? errors.email[0] : null}
        >
          <Input onChange={handleInputChange} />
        </Form.Item>
        <Form.Item
          name="password"
          label="Password"
          rules={[
            {
              required: true,
              message: "Please input the password of user!",
            },
          ]}
          validateStatus={errors.password ? "error" : null}
          help={errors.password ? errors.password[0] : null}
        >
          <Input.Password autoComplete="new-password" onChange={handleInputChange} />
        </Form.Item>

        <Form.Item
          name="password_confirmation"
          label="Password Confirm"
          dependencies={["password"]}
          rules={[
            {
              required: true,
              message: "Please input the password confirmation of user!",
            },
            ({ getFieldValue }) => ({
              validator(_, value) {
                if (!value || getFieldValue("password") === value) {
                  return Promise.resolve();
                }
                return Promise.reject(
                  new Error("The new password that you entered do not match!"),
                );
              },
            }),
          ]}
          validateStatus={errors.password_comfirmation ? "error" : null}
          help={
            errors.password_comfirmation
              ? errors.password_comfirmation[0]
              : null
          }
        >
          <Input.Password onChange={handleInputChange} />
        </Form.Item>
      </Form>
    </Modal>
  );
};

export default UserCreateForm;
