import { Form, Image, Input, Modal, Select, Switch } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";

const MediaUpdateModal = (props) => {
  const { previewImage, previewOpen, previewTitle, handleCancel, media } =
    props;
  const [imageUpdateForm] = Form.useForm();

  const handleImageUpdateFormInputChange = (e) => {
    // const { name, value } = e.target;
    // imageUpdateForm.setFieldsValue({
    //   [name]: value,
    //   errors: [],
    // });
  };

  const sendUpdateImageForm = () => {
    imageUpdateForm
      .validateFields()
      .then((values) => {
        imageUpdateForm.resetFields();
        updateMedia(values);
        handleCancel();
      })
      .catch((err) => {});
  };

  const updateMedia = (payload) => {
    axiosClient
      .put("/medias/" + media.id, payload)
      .then(({ data }) => {
        console.log(data);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  return (
    <Modal
      destroyOnClose={true}
      open={previewOpen}
      title={previewTitle}
      okText="Save"
      onCancel={handleCancel}
      onOk={sendUpdateImageForm}
    >
      <Image height={200} src={previewImage} />

      <Form
        labelCol={{
          span: 6,
        }}
        wrapperCol={{
          span: 18,
        }}
        labelAlign="left"
        style={{ marginTop: 16 }}
        form={imageUpdateForm}
        initialValues={media.custom_properties}
        preserve={false}
      >
        <Form.Item
          valuePropName="checked"
          name="primary"
          label="Primary image"
          rules={[]}
        >
          <Switch checkedChildren="Yes" unCheckedChildren="No" />
        </Form.Item>

        <Form.Item name="alt" label="Alternative text" rules={[]}>
          <Input onChange={handleImageUpdateFormInputChange} />
        </Form.Item>

        <Form.Item name="tooltip" label="Tooltip" rules={[]}>
          <Input onChange={handleImageUpdateFormInputChange} />
        </Form.Item>

        <Form.Item name="integrations" label="Integrations" rules={[]}>
          <Select
            showSearch
            allowClear
            mode="multiple"
            options={[
              {
                label: "Zoho Books",
                value: "zoho_books",
              },
              {
                value: "zoho_crm",
                label: "Zoho CRM",
              },
            ]}
          />
        </Form.Item>
      </Form>
    </Modal>
  );
};

export default MediaUpdateModal;
