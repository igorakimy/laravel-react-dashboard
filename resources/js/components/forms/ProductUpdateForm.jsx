import {
  Button,
  Card,
  Checkbox,
  Col,
  Divider,
  Dropdown,
  Form,
  Image,
  Input,
  InputNumber,
  message,
  Modal,
  Row,
  Select,
  Space,
  Switch,
  Tabs,
  Typography,
  Upload,
} from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import TextArea from "antd/es/input/TextArea";
import {
  DeleteOutlined,
  DownOutlined,
  EditOutlined,
  ExclamationCircleFilled,
  InboxOutlined,
  PictureOutlined,
} from "@ant-design/icons";
import ComponentsTable from "../tables/ComponentsTable.jsx";
import MediaUpdateModal from "../modals/MediaUpdateModal.jsx";

const ProductUpdateForm = ({
  open,
  product,
  onUpdate,
  onCancel,
  errors,
  setErrors,
}) => {
  const [messageApi, contextHolder] = message.useMessage();
  const [form] = Form.useForm();
  const [imageUpdateForm] = Form.useForm();
  const [bulkEditForm] = Form.useForm();
  const [clientReady, setClientReady] = useState(true);
  const [categories, setCategories] = useState([]);
  const [colors, setColors] = useState([]);
  const [materials, setMaterials] = useState([]);
  const [vendors, setVendors] = useState([]);
  const [types, setTypes] = useState([]);
  const [statuses, setStatuses] = useState([]);
  const [media, setMedia] = useState({});
  const [selectedMedia, setSelectedMedia] = useState([]);

  const [previewOpen, setPreviewOpen] = useState(false);
  const [previewImage, setPreviewImage] = useState("");
  const [previewTitle, setPreviewTitle] = useState("");

  // const [fileList, setFileList] = useState([]);
  const [catalogFileList, setCatalogFileList] = useState([]);
  const [productFileList, setProductFileList] = useState([]);
  const [vectorFileList, setVectorFileList] = useState([]);

  const [componentsOpen, setComponentsOpen] = useState(false);

  const [bulkActionsVisible, setBulkActionsVisible] = useState(false);
  const [bulkEditOpen, setBulkEditOpen] = useState(false);

  const { Title } = Typography;
  const { Dragger } = Upload;
  const { confirm } = Modal;

  useEffect(() => {
    getCategories();
    getColors();
    getMaterials();
    getVendors();
    getTypes();

    setErrors({});
    setBulkActionsVisible(false);

    form.setFieldsValue({
      name: product.name,
      sku: product.sku,
      cost_price: product.cost_price,
      selling_price: product.selling_price,
      margin: product.margin,
      width: product.width,
      height: product.height,
      weight: product.weight,
      location: product.location,
      color_id: product.color?.id,
      material_id: product.material?.id,
      vendor_id: product.vendor?.id,
      type_id: product.type?.id,
      quantity: product.quantity,
      categories: product.categories?.map((c) => {
        return c.id;
      }),
      barcode: product.barcode,
      caption: product.caption,
      description: product.description,
    });

    setCatalogFileList(
      product.media
        ? product.media
            .filter((m) => m.collection_name === "catalog_images")
            .map((m) => {
              return {
                name: m.file_name,
                type: m.mime_type,
                size: m.size,
                url: m.url,
                response: {
                  uuid: m.id,
                  alt: m.custom_properties?.alt && "",
                  tooltip: m.custom_properties?.tooltip && "",
                  primary: m.custom_properties?.primary && "",
                  integrations: m.custom_properties?.integrations && "",
                },
              };
            })
        : [],
    );

    setProductFileList(
      product.media
        ? product.media
            .filter((m) => m.collection_name === "product_images")
            .map((m) => {
              return {
                name: m.file_name,
                type: m.mime_type,
                size: m.size,
                url: m.url,
                response: { uuid: m.id },
              };
            })
        : [],
    );

    setVectorFileList(
      product.media
        ? product.media
            .filter((m) => m.collection_name === "vector_images")
            .map((m) => {
              return {
                name: m.file_name,
                type: m.mime_type,
                size: m.size,
                url: m.url,
                response: { uuid: m.id },
              };
            })
        : [],
    );
  }, [product]);

  const getBase64 = (file) =>
    new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result);
      reader.onerror = (error) => reject(error);
    });

  const handleCancel = () => setPreviewOpen(false);

  const handleCancelBulkEdit = () => setBulkEditOpen(false);

  const handlePreview = async (file) => {
    if (!file.url && !file.preview) {
      file.preview = await getBase64(file.originFileObj);
    }

    setPreviewImage(file.url || file.preview);
    setPreviewOpen(true);
    setPreviewTitle(
      file.name || file.url.substring(file.url.lastIndexOf("/") + 1),
    );

    getMedia(file.response.uuid);
    // console.log(imageUpdateForm);
  };

  const catalogFilesOnChange = ({ fileList: newFileList }) => {
    setCatalogFileList(newFileList);
  };

  const productFilesOnChange = ({ fileList: newFileList }) => {
    setProductFileList(newFileList);
  };

  const vectorFilesOnChange = ({ fileList: newFileList }) => {
    setVectorFileList(newFileList);
  };

  const beforeUpload = (file, box) => {
    const isCatalogImages = box === "catalog_images";
    const isProductImages = box === "product_images";
    const isVectorImages = box === "vector_images";

    const catalogFormats = ["image/jpeg", "image/png", "image/gif"];
    const productFormats = ["image/jpeg", "image/png", "image/gif"];
    const vectorFormats = ["image/svg", "image/svg+xml"];

    const isLt5M = file.size / 1024 / 1024 < 5;

    let isAvailableFormat = true;

    if (isCatalogImages && !catalogFormats.includes(file.type)) {
      isAvailableFormat = catalogFormats.includes(file.type);
      message.error("You can upload only JPG/PNG/GIF file!");
    }

    if (isProductImages && !productFormats.includes(file.type)) {
      isAvailableFormat = productFormats.includes(file.type);
      message.error("You can upload only JPG/PNG/GIF file!");
    }

    if (isVectorImages && !vectorFormats.includes(file.type)) {
      isAvailableFormat = vectorFormats.includes(file.type);
      message.error("You can upload only SVG file!");
    }

    if (!isLt5M) {
      message.error("Image must smaller than 5MB!");
    }

    return isAvailableFormat && isLt5M;
  };

  const handleRemoveUploadedFile = (file) => {
    const uuid = file.response?.uuid || 0;

    if (uuid === 0) {
      return true;
    }

    axiosClient
      .delete("/products/" + product.id + "/delete-media/" + uuid)
      .then((response) => {
        return true;
      })
      .catch((err) => {
        console.log(err);
        return false;
      });
  };

  const getMedia = (mediaId) => {
    axiosClient
      .get("/medias/" + mediaId)
      .then(({ data }) => {
        setMedia(data);
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
        onUpdate(product?.id, values);
        // setTimeout(form.resetFields, 500);
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

  const handleCheckedImage = (e, mediaId) => {
    let arr = selectedMedia;

    if (e.target.checked) {
      arr.push(mediaId);
    } else {
      arr = selectedMedia.filter((m) => {
        return m !== mediaId;
      });
    }
    setBulkActionsVisible(arr.length > 0);
    setSelectedMedia(arr);
  };

  const handleChange = (name) => (value) => {
    form.setFieldsValue({
      [name]: value,
      errors: [],
    });
  };

  const onValuesChange = (values) => {
    Object.keys(values).forEach((field) => {
      const error = form.getFieldError(field);
      if (!error.length) {
        return;
      }
      form.setFields([
        {
          name: field,
          errors: [],
        },
      ]);
    });
  };

  const handleProductIsComposite = (value) => {
    setComponentsOpen(value);
  };

  const handleItemRender = (originNode, file, fileList, actions) => {
    return (
      <div
        className={`ant-upload-list-item ant-upload-list-item-${file.status}`}
        title={file.name ?? file.response.name}
      >
        <a
          className="ant-upload-list-item-thumbnail"
          href={file.thumbUrl ?? file.url}
          target="_blank"
          rel="noopener noreferrer"
        >
          <img
            src={file.thumbUrl ?? file.url}
            alt={file.thumbUrl ?? file.url}
            className="ant-upload-list-item-image"
          />
        </a>
        <span
          className="ant-upload-list-item-name"
          title={file.name ?? file.response.name}
        >
          {file.name ?? file.response.name}
        </span>
        <Checkbox
          onChange={(e) =>
            handleCheckedImage(e, file.response.uuid ?? file.uid)
          }
          style={{
            position: "absolute",
            top: 6,
            left: 8,
            zIndex: 999,
          }}
        />
        <span className="ant-upload-list-item-actions">
          <EditOutlined
            title="Edit"
            className="anticon-delete"
            onClick={() => actions.preview()}
          />
          <DeleteOutlined title="Delete" onClick={() => actions.remove()} />
        </span>
      </div>
    );
  };

  const handleBulkItem = (e) => {
    if (e.key === "1") {
      handleBulkEdit();
    } else if (e.key === "2") {
      showDeleteConfirm();
    }
  };

  const handleBulkEdit = () => {
    setBulkEditOpen(true);
  };

  const handleBulkUpdate = (ids) => {
    bulkEditForm.validateFields().then((values) => {
      bulkEditForm.resetFields();
      axiosClient
        .put("/medias/bulk-update", { ids: ids, ...values })
        .then(({ data }) => {
          messageApi.success(data.message);
        })
        .catch((err) => {
          messageApi.error(err.message);
        });

      handleCancelBulkEdit();
    });
  };

  const showDeleteConfirm = () => {
    confirm({
      title: "Are you sure delete these images?",
      icon: <ExclamationCircleFilled />,
      content:
        "Selected images will be removed forever and you can not restore it.",
      okText: "Yes",
      okButtonProps: {
        type: "primary",
        danger: true,
      },
      cancelText: "No",
      onOk() {
        axiosClient
          .delete("/medias/bulk-delete?ids=" + selectedMedia.join(","))
          .then(({ data }) => {
            catalogFilesOnChange({
              fileList: catalogFileList.filter((f) => {
                return !selectedMedia.includes(f.response.uuid);
              }),
            });

            setSelectedMedia([]);
            setBulkActionsVisible(false);

            messageApi.success(data.message);
          })
          .catch((err) => {});
      },
      onCancel() {
        console.log("Cancel");
      },
    });
  };

  // const sendUpdateImageForm = () => {
  //   imageUpdateForm
  //     .validateFields()
  //     .then((values) => {
  //       console.log(values);
  //     })
  //     .catch((err) => {});
  // };

  // const handleImageUpdateFormInputChange = (e) => {
  //   const { name, value } = e.target;
  //   imageUpdateForm.setFieldsValue({
  //     [name]: value,
  //     errors: [],
  //   });
  // };

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
      width={1215}
    >
      {contextHolder}
      <Title level={4}>
        Edit Product <h2 style={{ display: "inline" }}>"{product.name}"</h2>
      </Title>
      <Divider style={{ margin: "0.6rem 0" }}></Divider>
      <Tabs
        defaultActiveKey="1"
        tabPosition="left"
        items={[
          {
            key: "1",
            label: "Data",
            children: (
              <Form
                labelCol={{ span: 8 }}
                wrapperCol={{ span: 16 }}
                form={form}
                initialValues={product}
                labelAlign="left"
                onValuesChange={onValuesChange}
                onFieldsChange={() =>
                  setClientReady(
                    !form.isFieldsTouched(true) ||
                      form
                        .getFieldsError()
                        .some((field) => field.errors.length > 0),
                  )
                }
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
                          message:
                            "Please input the quantity in stock of product",
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
                      help={
                        errors.selling_price ? errors.selling_price[0] : null
                      }
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
                      name="color_id"
                      label="Color"
                      rules={[]}
                      validateStatus={errors.color_id ? "error" : null}
                      help={errors.color_id ? errors.color_id[0] : null}
                    >
                      <Select showSearch options={colors} />
                    </Form.Item>

                    <Form.Item
                      name="material_id"
                      label="Material"
                      rules={[]}
                      validateStatus={errors.material_id ? "error" : null}
                      help={errors.material_id ? errors.material_id[0] : null}
                    >
                      <Select showSearch options={materials} />
                    </Form.Item>

                    <Form.Item
                      name="vendor_id"
                      label="Vendor"
                      rules={[]}
                      validateStatus={errors.vendor_id ? "error" : null}
                      help={errors.vendor_id ? errors.vendor_id[0] : null}
                    >
                      <Select showSearch options={vendors} />
                    </Form.Item>

                    <Form.Item
                      name="type_id"
                      label="Type"
                      rules={[]}
                      validateStatus={errors.type_id ? "error" : null}
                      help={errors.type_id ? errors.type_id[0] : null}
                    >
                      <Select showSearch options={types} />
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
            ),
          },
          {
            label: "Images",
            key: "2",
            children: (
              <Space
                direction="vertical"
                size="middle"
                style={{ display: "flex" }}
              >
                <Card
                  size="small"
                  title="Catalog Images"
                  extra={
                    <Dropdown
                      menu={{
                        items: [
                          {
                            key: "1",
                            label: "Edit",
                          },
                          {
                            key: "2",
                            label: "Delete",
                          },
                        ],
                        onClick: handleBulkItem,
                      }}
                    >
                      <Button
                        size="small"
                        style={{
                          display: bulkActionsVisible ? "block" : "none",
                          float: "right",
                          marginRight: "1rem",
                        }}
                      >
                        <Space>
                          Bulk Actions
                          <DownOutlined />
                        </Space>
                      </Button>
                    </Dropdown>
                  }
                >
                  <Dragger
                    fileList={catalogFileList}
                    name="catalog_images"
                    customRequest={({
                      file,
                      onSuccess,
                      onError,
                      onProgress,
                    }) => {
                      let formData = new FormData();
                      formData.append("image", file);
                      axiosClient
                        .post(
                          "/products/" +
                            product.id +
                            "/upload-media/catalog_images",
                          formData,
                          {
                            onUploadProgress: (event) => {
                              let percent = Math.floor(
                                (event.loaded / event.total) * 100,
                              );
                              onProgress({ percent });
                            },
                          },
                        )
                        .then((response) => {
                          onSuccess({ uuid: response.data?.id });
                        })
                        .catch((error) => {
                          onError(error);
                        });
                    }}
                    multiple={true}
                    listType="picture-card"
                    style={{
                      marginBottom: 8,
                    }}
                    onPreview={handlePreview}
                    onChange={catalogFilesOnChange}
                    itemRender={handleItemRender}
                    onRemove={handleRemoveUploadedFile}
                    beforeUpload={(file) =>
                      beforeUpload(file, "catalog_images")
                    }
                  >
                    <p className="ant-upload-drag-icon">
                      <InboxOutlined />
                    </p>
                    <p className="ant-upload-text">
                      Click or drag image to this area to upload
                    </p>
                    <p className="ant-upload-hint">
                      Support for a single or bulk upload. Strictly prohibited
                      from uploading company data or other banned files.
                    </p>
                  </Dragger>
                  <Modal
                    open={bulkEditOpen}
                    title="Bulk edit"
                    maskClosable={false}
                    okText="Update"
                    cancelText="Cancel"
                    onCancel={handleCancelBulkEdit}
                    onOk={() => handleBulkUpdate(selectedMedia)}
                  >
                    <Form
                      labelCol={{
                        span: 6,
                      }}
                      wrapperCol={{
                        span: 18,
                      }}
                      labelAlign="left"
                      form={bulkEditForm}
                    >
                      <Form.Item
                        name="integrations"
                        label="Integrations"
                        rules={[]}
                      >
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
                </Card>
                <Card size="small" title="Product Images">
                  <Dragger
                    fileList={productFileList}
                    name="product_images"
                    customRequest={({
                      file,
                      onSuccess,
                      onError,
                      onProgress,
                    }) => {
                      let formData = new FormData();
                      formData.append("image", file);
                      axiosClient
                        .post(
                          "/products/" +
                            product.id +
                            "/upload-media/product_images",
                          formData,
                          {
                            onUploadProgress: (event) => {
                              let percent = Math.floor(
                                (event.loaded / event.total) * 100,
                              );
                              onProgress({ percent });
                            },
                          },
                        )
                        .then((response) => {
                          onSuccess({ uuid: response.data?.id });
                        })
                        .catch((error) => {
                          onError(error);
                        });
                    }}
                    multiple={true}
                    listType="picture-card"
                    style={{
                      marginBottom: 8,
                    }}
                    onPreview={handlePreview}
                    onChange={productFilesOnChange}
                    onRemove={handleRemoveUploadedFile}
                    itemRender={handleItemRender}
                    beforeUpload={(file) =>
                      beforeUpload(file, "product_images")
                    }
                  >
                    <p className="ant-upload-drag-icon">
                      <InboxOutlined />
                    </p>
                    <p className="ant-upload-text">
                      Click or drag image to this area to upload
                    </p>
                    <p className="ant-upload-hint">
                      Support for a single or bulk upload. Strictly prohibited
                      from uploading company data or other banned files.
                    </p>
                  </Dragger>
                </Card>
                <Card size="small" title="Vector Images">
                  <Dragger
                    fileList={vectorFileList}
                    name="vector_images"
                    maxCount={1}
                    customRequest={({
                      file,
                      onSuccess,
                      onError,
                      onProgress,
                    }) => {
                      let formData = new FormData();
                      formData.append("vector_image", file);
                      axiosClient
                        .post(
                          "/products/" +
                            product.id +
                            "/upload-media/vector_image",
                          formData,
                          {
                            onUploadProgress: (event) => {
                              let percent = Math.floor(
                                (event.loaded / event.total) * 100,
                              );
                              onProgress({ percent });
                            },
                          },
                        )
                        .then((response) => {
                          onSuccess({ uuid: response.data?.id });
                        })
                        .catch((error) => {
                          onError(error);
                        });
                    }}
                    multiple={true}
                    listType="picture-card"
                    style={{
                      marginBottom: 8,
                    }}
                    onPreview={handlePreview}
                    onChange={vectorFilesOnChange}
                    onRemove={handleRemoveUploadedFile}
                    itemRender={handleItemRender}
                    beforeUpload={(file) => beforeUpload(file, "vector_images")}
                  >
                    <p className="ant-upload-drag-icon">
                      <InboxOutlined />
                    </p>
                    <p className="ant-upload-text">
                      Click or drag image to this area to upload
                    </p>
                    <p className="ant-upload-hint">
                      Support for a single or bulk upload. Strictly prohibited
                      from uploading company data or other banned files.
                    </p>
                  </Dragger>
                </Card>

                <MediaUpdateModal
                  previewImage={previewImage}
                  previewOpen={previewOpen}
                  previewTitle={previewTitle}
                  handleCancel={handleCancel}
                  media={media}
                />
              </Space>
            ),
          },
          {
            key: "3",
            label: "Components",
            children: (
              <>
                <Switch
                  checkedChildren="Composite Product"
                  unCheckedChildren="Simple Product"
                  onChange={handleProductIsComposite}
                  size="default"
                />
                <ComponentsTable open={componentsOpen} product={product} />
              </>
            ),
          },
        ]}
      />
    </Modal>
  );
};

export default ProductUpdateForm;
