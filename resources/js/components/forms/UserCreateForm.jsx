import { Divider, Form, Input, Modal, Select, Typography } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";

const UserCreateForm = ({ open, onCreate, onCancel, errors, onError }) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [roles, setRoles] = useState([]);

  const { Title } = Typography;

  useEffect(() => {
    getRoles();
  }, []);

  const getRoles = () => {
    axiosClient
      .get("/roles?for_select=true")
      .then(({ data }) => {
        if (!data) return;
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

  const handleSelectChange = (value) => {
    console.log(value);
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
        top: 20,
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
          name="first_name"
          label="First Name"
          rules={[
            {
              required: true,
              message: "Please input the first name of user!",
            },
          ]}
          validateStatus={errors.first_name ? "error" : null}
          help={errors.first_name ? errors.first_name[0] : null}
        >
          <Input onChange={handleInputChange} />
        </Form.Item>
        <Form.Item
          name="last_name"
          label="Last Name"
          rules={[
            {
              required: true,
              message: "Please input the last name of user!",
            },
          ]}
          validateStatus={errors.last_name ? "error" : null}
          help={errors.last_name ? errors.last_name[0] : null}
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
          name="roles"
          label="Roles"
          rules={[
            {
              required: true,
              message: "Please select user role",
              type: "array",
            },
          ]}
          validateStatus={errors.roles ? "error" : null}
          help={errors.roles ? errors.roles[0] : null}
        >
          <Select
            showSearch
            mode="multiple"
            options={roles}
            onChange={handleSelectChange}
          />
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
          <Input.Password
            autoComplete="new-password"
            onChange={handleInputChange}
          />
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
