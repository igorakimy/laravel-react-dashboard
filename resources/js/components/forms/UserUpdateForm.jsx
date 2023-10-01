import { Divider, Form, Input, Modal, Select, Typography } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";

const UserUpdateForm = ({
  open,
  user,
  onUpdate,
  onCancel,
  errors,
  onError,
}) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [roles, setRoles] = useState([]);

  const { Title } = Typography;

  form.setFieldValue(
    "roles",
    user.roles?.map((r) => {
      return r.id;
    }),
  );

  useEffect(() => {
    form.setFieldsValue(user);
    getRoles();
  }, [user]);

  const sendForm = () => {
    form
      .validateFields()
      .then((values) => {
        onUpdate(user?.id, values);
        onError();

        form.resetFields();
      })
      .catch((err) => {});
  };

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
      okText="Update"
      cancelText="Cancel"
      onCancel={onCancel}
      onOk={sendForm}
      style={{
        top: 20,
      }}
    >
      <Title level={4}>Edit User "{user.name}"</Title>
      <Divider style={{ margin: "0.6rem 0" }}></Divider>
      <Form
        labelCol={{ span: 8 }}
        wrapperCol={{ span: 16 }}
        form={form}
        initialValues={user}
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
          id="update_user_email"
          rules={[
            {
              required: true,
              message: "Please input the email of user",
            },
            {
              type: "email",
              message: "The input is not valid email",
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
          <Select showSearch allowClear mode="multiple" options={roles} />
        </Form.Item>
        <Form.Item
          name="password"
          label="New Password"
          id="update_user_password"
          validateStatus={errors.password ? "error" : null}
          help={errors.password ? errors.password[0] : null}
        >
          <Input.Password onChange={handleInputChange} />
        </Form.Item>
        <Form.Item
          fi
          name="password_confirmation"
          label="Password Confirm"
          id="update_user_password_confirm"
          dependencies={["password"]}
          rules={[
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

export default UserUpdateForm;
