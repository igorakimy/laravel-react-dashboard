import {
  Col,
  Divider,
  Form,
  Input,
  InputNumber,
  Modal,
  Row,
  Select,
  Typography,
} from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import TextArea from "antd/es/input/TextArea";

const ProductCreateForm = ({ open, onCreate, onCancel, errors, onError }) => {
  const [form] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [localFields, setLocalFields] = useState([]);
  const [integrations, setIntegrations] = useState([]);
  const [categories, setCategories] = useState([]);
  const [colors, setColors] = useState([]);
  const [materials, setMaterials] = useState([]);
  const [vendors, setVendors] = useState([]);
  const [types, setTypes] = useState([]);

  const { Title } = Typography;

  useEffect(() => {
    clearInputErrors();
    getLocalFields();
    getIntegrations();
    getCategories();
    getColors();
    getMaterials();
    getVendors();
    getTypes();
  }, []);

  const clearInputErrors = () => {
    // console.log(form.resetFields());
    // form.setFields(
    //   form.getFieldsError()?.map((err) => {
    //     return {
    //       name: err.name[0],
    //       value: "",
    //       validated: true,
    //     };
    //   }),
    // );
  };

  const getLocalFields = () => {
    axiosClient
      .get("/local-fields")
      .then(({ data }) => {
        setLocalFields(data);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getIntegrations = () => {
    axiosClient
      .get("/integrations")
      .then(({ data }) => {
        setIntegrations(data);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getCategories = () => {
    axiosClient
      .get("/categories?kind=select")
      .then(({ data }) => {
        setCategories(
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

  const getColors = () => {
    axiosClient
      .get("/colors")
      .then(({ data }) => {
        setColors(
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

  const getMaterials = () => {
    axiosClient
      .get("/materials")
      .then(({ data }) => {
        setMaterials(
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

  const getVendors = () => {
    axiosClient
      .get("/vendors")
      .then(({ data }) => {
        setVendors(
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

  const getTypes = () => {
    axiosClient
      .get("/types")
      .then(({ data }) => {
        setTypes(
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

  const handleChange = (name) => (value) => {
    form.setFieldsValue({
      [name]: value,
      errors: [],
    });
  };

  const getFieldValidationRules = (field) => {
    let rules = [];

    if (field.validations?.required) {
      rules.push({
        required: true,
        message: `${field.name} field is required`,
      });
    }

    return rules;
  };

  const getOptionsForResource = (resourceName) => {
    switch (resourceName) {
      case "categories":
        return categories;
      case "color_id":
        return colors;
      case "material_id":
        return materials;
      case "vendor_id":
        return vendors;
      case "type_id":
        return types;
      default:
        return [];
    }
  };

  const buildFormItems = (field) => {
    return (
      <Form.Item
        fieldId={field.slug}
        name={field.slug}
        label={field.name}
        rules={getFieldValidationRules(field)}
        validateStatus={errors[field.slug] ? "error" : null}
        help={errors[field.slug] ? errors[field.slug][0] : null}
      >
        {field.field_type === "text" && <Input onChange={handleInputChange} />}

        {field.field_type === "number" && (
          <InputNumber
            addonAfter={field.properties.addon ?? null}
            style={{
              width: "100%",
            }}
            min={field.validations.min}
            max={field.validations.max}
            defaultValue={field.default_value ?? 0}
            onChange={handleChange(field.slug)}
          />
        )}

        {field.field_type === "select" && (
          <Select
            allowClear={!field.validations?.required}
            showSearch
            options={getOptionsForResource(field.slug)}
          />
        )}

        {field.field_type === "multiselect" && (
          <Select
            showSearch
            allowClear
            mode="multiple"
            options={getOptionsForResource(field.slug)}
          />
        )}

        {field.field_type === "textarea" && (
          <TextArea onChange={handleInputChange} />
        )}
      </Form.Item>
    );
  };

  return (
    <Modal
      destroyOnClose={true}
      maskClosable={false}
      open={open}
      okText="Create"
      okButtonProps={{
        disabled: false,
      }}
      cancelText="Cancel"
      onCancel={onCancel}
      onOk={sendForm}
      style={{
        top: 20,
      }}
      width={1000}
    >
      <Title level={4}>New Product</Title>
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
        <Row gutter={48}>
          <Col key="1" span={12}>
            {localFields
              .slice(0, Math.floor(localFields.length / 2))
              .map((field) => {
                return buildFormItems(field);
              })}
          </Col>
          <Col key="2" span={12}>
            {localFields
              .slice(Math.floor(localFields.length / 2))
              .map((field) => {
                return buildFormItems(field);
              })}
          </Col>
        </Row>
      </Form>
    </Modal>
  );
};

export default ProductCreateForm;
