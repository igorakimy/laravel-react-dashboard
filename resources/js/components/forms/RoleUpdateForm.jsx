import { Divider, Form, Input, Modal, Select, Typography } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";

const RoleUpdateForm = ({
  open,
  role,
  onUpdate,
  onCancel,
  errors,
  onError,
}) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [permissions, setPermissions] = useState([]);

  const { Title } = Typography;

  useEffect(() => {
    form.setFieldsValue(role);
    form.setFieldValue(
      "permissions",
      role.permissions?.map((p) => {
        return p.id;
      }),
    );
    getPermissions();
  }, [role]);

  const sendForm = () => {
    form
      .validateFields()
      .then((values) => {
        onUpdate(role?.id, values);
        onError();
      })
      .catch((err) => {});
  };

  const getPermissions = () => {
    axiosClient
      .get("/permissions?for_select=true")
      .then(({ data }) => {
        setPermissions(
          data.map((item) => {
            return {
              label: item.display_name,
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
      <Title level={4}>Edit Role "{role.name}"</Title>
      <Divider style={{ margin: "0.6rem 0" }}></Divider>
      <Form
        labelCol={{ span: 8 }}
        wrapperCol={{ span: 16 }}
        form={form}
        initialValues={role}
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
              message: "Please input the name of role",
            },
          ]}
          validateStatus={errors.name ? "error" : null}
          help={errors.name ? errors.name[0] : null}
        >
          <Input onChange={handleInputChange} />
        </Form.Item>
        <Form.Item
          name="permissions"
          label="Permissions"
          rules={[
            {
              required: true,
              message: "Please select permissions",
              type: "array",
            },
          ]}
          validateStatus={errors.permissions ? "error" : null}
          help={errors.permissions ? errors.permissions[0] : null}
        >
          <Select showSearch allowClear mode="multiple" options={permissions} />
        </Form.Item>
      </Form>
    </Modal>
  );
};

export default RoleUpdateForm;
