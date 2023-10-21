import {
  Button,
  Divider,
  Form,
  Input,
  Modal,
  Select,
  Space,
  Typography,
} from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import { CloseOutlined } from "@ant-design/icons";

const UserCreateForm = ({ open, onCreate, onCancel, errors, onError }) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [roles, setRoles] = useState([]);
  const [statuses, setStatuses] = useState([]);

  const { Title } = Typography;

  useEffect(() => {
    getRoles();
    getStatuses();
  }, []);

  const getRoles = () => {
    axiosClient
      .get("/roles?kind=select")
      .then(({ data }) => {
        if (!data) return;
        setRoles(
          data.map((item) => {
            return {
              label: item.name.charAt(0).toUpperCase() + item.name.slice(1),
              value: item.id,
            };
          }),
        );
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getStatuses = () => {
    axiosClient
      .get("/statuses")
      .then(({ data }) => {
        setStatuses(
          data.map((s) => {
            return {
              label: s,
              value: s,
            };
          }),
        );
      })
      .catch((err) => {});
  };

  const sendForm = () => {
    form
      .validateFields()
      .then((values) => {
        onCreate(values);
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
          <Select showSearch mode="multiple" options={roles} />
        </Form.Item>
        <Form.Item
          name="status"
          label="Status"
          rules={[
            {
              required: true,
              message: "Please set the user status",
            },
          ]}
          validateStatus={errors.status ? "error" : null}
          help={errors.status ? errors.status[0] : null}
        >
          <Select options={statuses} />
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

        <Form.Item label="Phones">
          <Form.List name="phones">
            {(fields, { add, remove }, { errors }) => (
              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  width: "100%",
                  rowGap: 16,
                }}
              >
                {fields.map((field) => (
                  <Space key={field.key}>
                    <Form.Item noStyle name={[field.name]}>
                      <Input placeholder="phone number" />
                    </Form.Item>
                    <CloseOutlined
                      onClick={() => {
                        console.log(field, fields);
                        remove(field.name);
                      }}
                    />
                  </Space>
                ))}
                <Button type="dashed" onClick={() => add()} block>
                  + Add Phone
                </Button>
                <Form.ErrorList errors={errors} />
              </div>
            )}
          </Form.List>
        </Form.Item>
      </Form>
    </Modal>
  );
};

export default UserCreateForm;
