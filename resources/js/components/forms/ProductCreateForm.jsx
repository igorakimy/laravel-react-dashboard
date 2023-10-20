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
  const [categories, setCategories] = useState([]);
  const [colors, setColors] = useState([]);
  const [materials, setMaterials] = useState([]);
  const [vendors, setVendors] = useState([]);
  const [types, setTypes] = useState([]);

  const { Title } = Typography;

  useEffect(() => {
    getCategories();
    getColors();
    getMaterials();
    getVendors();
    getTypes();
  }, []);

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

  return (
    <Modal
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
        // onFieldsChange={() =>
        //   setClientReady(
        //     !form.isFieldsTouched(true) ||
        //       form.getFieldsError().some((field) => field.errors.length > 0),
        //   )
        // }
      >
        <Row gutter={48}>
          <Col key="1" span={12}>
            <Form.Item
              name="name"
              label="Name"
              rules={[
                {
                  required: true,
                  message: "Please input the name of product",
                },
              ]}
              validateStatus={errors.name ? "error" : null}
              help={errors.name ? errors.name[0] : null}
            >
              <Input onChange={handleInputChange} />
            </Form.Item>

            <Form.Item
              name="sku"
              label="SKU"
              rules={[
                {
                  required: true,
                  message: "Please input the SKU of product",
                },
              ]}
              validateStatus={errors.sku ? "error" : null}
              help={errors.sku ? errors.sku[0] : null}
            >
              <Input onChange={handleInputChange} />
            </Form.Item>

            <Form.Item
              name="quantity"
              label="Quantity"
              rules={[
                {
                  required: true,
                  message: "Please input the quantity in stock of product",
                },
              ]}
              validateStatus={errors.quantity ? "error" : null}
              help={errors.quantity ? errors.quantity[0] : null}
            >
              <InputNumber
                style={{
                  width: "100%",
                }}
                min={0}
                max={9999}
                defaultValue={1}
                onChange={handleChange("quantity")}
              />
            </Form.Item>

            <Form.Item
              name="cost_price"
              label="Cost Price"
              rules={[
                {
                  required: true,
                  message: "Please input the cost price of product",
                },
              ]}
              validateStatus={errors.cost_price ? "error" : null}
              help={errors.cost_price ? errors.cost_price[0] : null}
            >
              <InputNumber
                style={{
                  width: "100%",
                }}
                min={0}
                max={9999}
                addonAfter="$"
                onChange={handleChange("cost_price")}
              />
            </Form.Item>

            <Form.Item
              name="selling_price"
              label="Selling Price"
              rules={[
                {
                  required: true,
                  message: "Please input the selling price of product",
                },
              ]}
              validateStatus={errors.selling_price ? "error" : null}
              help={errors.selling_price ? errors.selling_price[0] : null}
            >
              <InputNumber
                style={{
                  width: "100%",
                }}
                min={0}
                max={9999}
                addonAfter="$"
                onChange={handleChange("selling_price")}
              />
            </Form.Item>

            <Form.Item
              name="margin"
              label="Margin"
              rules={[]}
              validateStatus={errors.margin ? "error" : null}
              help={errors.margin ? errors.margin[0] : null}
            >
              <InputNumber
                style={{
                  width: "100%",
                }}
                min={0}
                max={9999}
                step={0.1}
                defaultValue={0.0}
                addonAfter="%"
                onChange={handleChange("margin")}
              />
            </Form.Item>

            <Form.Item
              name="width"
              label="Width"
              rules={[
                {
                  required: true,
                  message: "Please input the width of product",
                },
              ]}
              validateStatus={errors.width ? "error" : null}
              help={errors.width ? errors.width[0] : null}
            >
              <InputNumber
                style={{
                  width: "100%",
                }}
                min={0}
                max={9999}
                defaultValue={0}
                onChange={handleChange("width")}
              />
            </Form.Item>

            <Form.Item
              name="height"
              label="Height"
              rules={[
                {
                  required: true,
                  message: "Please input the height of product",
                },
              ]}
              validateStatus={errors.height ? "error" : null}
              help={errors.height ? errors.height[0] : null}
            >
              <InputNumber
                style={{
                  width: "100%",
                }}
                min={0}
                max={9999}
                defaultValue={0}
                onChange={handleChange("height")}
              />
            </Form.Item>

            <Form.Item
              name="weight"
              label="Weight"
              rules={[
                {
                  required: true,
                  message: "Please input the weight of product",
                },
              ]}
              validateStatus={errors.weight ? "error" : null}
              help={errors.weight ? errors.weight[0] : null}
            >
              <InputNumber
                style={{
                  width: "100%",
                }}
                min={0}
                max={9999}
                defaultValue={0}
                onChange={handleChange("weight")}
              />
            </Form.Item>
          </Col>

          <Col key="2" span={12}>
            <Form.Item
              name="barcode"
              label="Barcode/UPC"
              rules={[]}
              validateStatus={errors.barcode ? "error" : null}
              help={errors.barcode ? errors.barcode[0] : null}
            >
              <Input onChange={handleInputChange} />
            </Form.Item>

            <Form.Item
              name="location"
              label="Location/Bin Number"
              rules={[]}
              validateStatus={errors.location ? "error" : null}
              help={errors.location ? errors.location[0] : null}
            >
              <Input onChange={handleInputChange} />
            </Form.Item>

            <Form.Item
              name="color"
              label="Color"
              rules={[]}
              validateStatus={errors.color ? "error" : null}
              help={errors.color ? errors.color[0] : null}
            >
              <Select showSearch allowClear options={colors} />
            </Form.Item>

            <Form.Item
              name="material"
              label="Material"
              rules={[]}
              validateStatus={errors.material ? "error" : null}
              help={errors.material ? errors.material[0] : null}
            >
              <Select showSearch allowClear options={materials} />
            </Form.Item>

            <Form.Item
              name="vendor"
              label="Vendor"
              rules={[]}
              validateStatus={errors.vendor ? "error" : null}
              help={errors.vendor ? errors.vendor[0] : null}
            >
              <Select showSearch allowClear options={vendors} />
            </Form.Item>

            <Form.Item
              name="type"
              label="Type"
              rules={[]}
              validateStatus={errors.type ? "error" : null}
              help={errors.type ? errors.type[0] : null}
            >
              <Select showSearch allowClear options={types} />
            </Form.Item>

            <Form.Item
              name="categories"
              label="Categories"
              rules={[
                {
                  required: true,
                  message: "Please select categories",
                  type: "array",
                },
              ]}
              validateStatus={errors.categories ? "error" : null}
              help={errors.categories ? errors.categories[0] : null}
            >
              <Select
                showSearch
                allowClear
                mode="multiple"
                options={categories}
              />
            </Form.Item>

            <Form.Item
              name="caption"
              label="Caption"
              rules={[]}
              validateStatus={errors.caption ? "error" : null}
              help={errors.caption ? errors.caption[0] : null}
            >
              <TextArea onChange={handleInputChange} />
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
          </Col>
        </Row>
      </Form>
    </Modal>
  );
};

export default ProductCreateForm;
