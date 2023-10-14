import { Divider, Form, Input, Modal, Select, Typography } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import TextArea from "antd/es/input/TextArea.js";

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

  const { Title } = Typography;

  useEffect(() => {
    form.setFieldsValue(category);
    form.setFieldValue("parent", category.parent);
    getCategories();
  }, [category]);

  const getCategories = () => {
    axiosClient
      .get("/categories?for_select=true")
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
      <Title level={4}>Edit Category "{category.name}"</Title>
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
