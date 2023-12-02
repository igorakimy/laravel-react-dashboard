import {
  Button,
  Card,
  Col,
  Form,
  Input,
  message,
  Row,
  Select,
  Space,
} from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import { MinusCircleOutlined, PlusOutlined } from "@ant-design/icons";
import { Option } from "antd/es/mentions/index";

const ZohoBooksSettings = () => {
  const [externalPopup, setExternalPopup] = useState(null);
  const [authUrl, setAuthUrl] = useState("");
  const [localFields, setLocalFields] = useState([]);
  const [integrationFields, setIntegrationFields] = useState([]);
  const [mappedFields, setMappedFields] = useState([]);
  const [refreshToken, setRefreshToken] = useState(
    localStorage.getItem("zoho_books_refresh_token"),
  );
  const [expireToken, setExpireToken] = useState(
    localStorage.getItem("zoho_books_expire_token"),
  );
  const [settings, setSettings] = useState({});
  const [messageApi, contextHolder] = message.useMessage();

  const [form] = Form.useForm();
  const [fieldsMappingForm] = Form.useForm();

  useEffect(() => {
    getAuthUrl();
    getSettings();
    getLocalFields();
    getMappedFields("zoho-books");
    getIntegrationFields("zoho-books");

    mappedFields.map((field, index) => {
      const currentValues = fieldsMappingForm.getFieldsValue(true);
      currentValues[`fields[${index}].local_field`] =
        field.local_field === null ? 0 : field.local_field.id;
      currentValues[`fields[${index}].integration_field`] =
        field.integration_field.id;
      fieldsMappingForm.setFieldsValue(currentValues);
    });
  }, []);

  useEffect(() => {
    if (!externalPopup) {
      return;
    }

    const timer = setInterval(() => {
      if (!externalPopup || externalPopup.closed) {
        timer && clearInterval(timer);
        return;
      }

      const currentUrl = externalPopup.location.href;
      if (!currentUrl) {
        return;
      }

      const searchParams = new URL(currentUrl).searchParams;
      const code = searchParams.get("code");
      const error = searchParams.get("error");
      if (code) {
        externalPopup.close();
        showLoadingMessage("Connecting...");
        axiosClient
          .get("/integrations/zoho-books/callback?code=" + code)
          .then((response) => {
            if (response.data.access_token) {
              setRefreshToken(response.data.refresh_token);
              localStorage.setItem(
                "zoho_books_refresh_token",
                response.data.refresh_token,
              );

              const expiresIn = response.data.expires_in + Date.now() / 1000;
              setExpireToken(expiresIn);
              localStorage.setItem("zoho_books_expire_token", expiresIn);
              console.log(response.data.expires_in);
              setExternalPopup(null);
              timer && clearInterval(timer);
              messageApi.success("Zoho Books successfully connected!");
              getIntegrationFields("zoho-books");
            }
          })
          .catch((err) => {
            // message.error(err);
          })
          .finally(() => {
            // clear timer at the end
            setExternalPopup(null);
            timer && clearInterval(timer);
          });
      } else if (error) {
        externalPopup.close();
        setExternalPopup(null);
        timer && clearInterval(timer);
        messageApi.error("Connection was rejected!");
      }
    }, 300);
  }, [externalPopup]);

  const showLoadingMessage = (content) => {
    messageApi.open({
      key: "updatable",
      type: "loading",
      content: content,
    });
  };

  const getAuthUrl = () => {
    axiosClient
      .get("/integrations/zoho-books/authenticate")
      .then((response) => {
        setAuthUrl(response.data.auth_url);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getSettings = () => {
    axiosClient
      .get("/settings/zoho-books")
      .then((response) => {
        // console.log(response.data);
        setSettings(response.data);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getLocalFields = () => {
    axiosClient
      .get("/local-fields")
      .then((response) => {
        setLocalFields(response.data);
        console.log(response.data);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getIntegrationFields = (integration) => {
    axiosClient
      .get("/integration-fields/" + integration)
      .then((response) => {
        setIntegrationFields(response.data.data);
        console.log(response.data);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getMappedFields = (integration) => {
    axiosClient
      .get("/integration-fields/mapped/" + integration)
      .then((response) => {
        setMappedFields(response.data);
        console.log(
          response.data.map((field) => {
            return {
              local_field:
                field.local_field === null ? 0 : field.local_field.id,
              integration_field: field.integration_field.id,
            };
          }),
        );
        console.log([
          {
            local_field: 0,
            integration_field: 4,
          },
          {
            local_field: 5,
            integration_field: 2,
          },
        ]);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const handleConnectZohoBooks = (e) => {
    const width = 700;
    const height = 700;
    const left = window.screenX + (window.outerWidth - width) / 2;
    const top = window.screenY + (window.outerHeight - height) / 2.5;
    const title = `Connect To Zoho Books`;

    const popup = window.open(
      authUrl,
      title,
      `toolbar=0,scrollbars=1,status=1,resizable=0,location=1,menuBar=0,width=${width},height=${height},left=${left},top=${top}`,
    );
    setExternalPopup(popup);
  };

  const handleDisconnectZohoBooks = () => {
    localStorage.removeItem("zoho_books_refresh_token");
    localStorage.removeItem("zoho_books_expire_token");
    setRefreshToken("");
    setExpireToken(null);
    messageApi.success("Zoho Books successfully disconnected!");
  };

  const handleSaveZohoBooksConfig = (values) => {
    form.validateFields().then((values) => {
      axiosClient
        .put("/settings/zoho-books", values)
        .then((response) => {
          getSettings();
          getAuthUrl();
          messageApi.success("Zoho Books settings successfully saved!");
        })
        .catch((err) => {
          console.log(err);
        });
    });
  };

  const handleFieldsMapping = () => {
    fieldsMappingForm
      .validateFields()
      .then((values) => {
        axiosClient
          .post("/integration-fields/mapping/zoho-books", values)
          .then((response) => {
            messageApi.success("Zoho Books fields successfully mapped!");
          });
      })
      .catch((err) => {
        console.log(err);
      });
  };

  return (
    <Space
      direction="vertical"
      size="middle"
      style={{ width: 800, display: "flex" }}
    >
      {contextHolder}

      <Card
        type="inner"
        title="Connection"
        extra={
          <Space>
            {refreshToken && Date.now() / 1000 < expireToken ? (
              <Button
                onClick={handleDisconnectZohoBooks}
                size="small"
                type="primary"
                style={{
                  backgroundColor: "#ff4d4f",
                }}
              >
                Disconnect
              </Button>
            ) : (
              <Button
                size="small"
                onClick={handleConnectZohoBooks}
                type="primary"
              >
                Connect
              </Button>
            )}
            <Button
              type="primary"
              size="small"
              onClick={handleSaveZohoBooksConfig}
              style={{ backgroundColor: "green" }}
            >
              Save
            </Button>
          </Space>
        }
      >
        <Form
          form={form}
          labelCol={{ span: 4 }}
          wrapperCol={{ span: 20 }}
          labelAlign="left"
        >
          {Object.keys(settings).map((key) => {
            return (
              <Form.Item
                name={key}
                label={key[0].toUpperCase() + key.split("_").join(" ").slice(1)}
                initialValue={
                  Array.isArray(settings[key])
                    ? settings[key].join(",")
                    : settings[key]
                }
              >
                <Input />
              </Form.Item>
            );
          })}
        </Form>
      </Card>

      <Card
        type="inner"
        title="Map Fields"
        extra={
          <Space>
            <Button
              size="small"
              type="primary"
              onClick={handleFieldsMapping}
              style={{ backgroundColor: "green" }}
            >
              Save
            </Button>
          </Space>
        }
      >
        <Form name="fields_mapping" form={fieldsMappingForm} autoComplete="off">
          <Row gutter={16} style={{ marginBottom: 16 }}>
            <Col span={11}>Local Field</Col>
            <Col span={11}>Zoho Books Field</Col>
            <Col span={2}>Action</Col>
          </Row>

          {/*<Row gutter={16}>*/}
          {/*  <Col span={11}>*/}
          {/*    <Form.Item*/}
          {/*      name={["integration_fields", "local_field"]}*/}
          {/*      initialValue="Primary Field"*/}
          {/*      style={{*/}
          {/*        disabled: true,*/}
          {/*      }}*/}
          {/*    >*/}
          {/*      <Input />*/}
          {/*    </Form.Item>*/}
          {/*  </Col>*/}
          {/*  <Col span={11}>*/}
          {/*    <Form.Item*/}
          {/*      name={["integration_fields", "integration_field"]}*/}
          {/*      rules={[*/}
          {/*        {*/}
          {/*          required: true,*/}
          {/*          message: "Missing Zoho Books field",*/}
          {/*        },*/}
          {/*      ]}*/}
          {/*    >*/}
          {/*      <Select defaultValue="a2" options={[]} />*/}
          {/*    </Form.Item>*/}
          {/*  </Col>*/}
          {/*  <Col span={2}></Col>*/}
          {/*</Row>*/}

          <Form.List
            name="fields"
            // initialValue={[
            //   {
            //     local_field: 0,
            //     integration_field: 4,
            //   },
            //   {
            //     local_field: 5,
            //     integration_field: 2,
            //   },
            // ]}

            initialValue={
              mappedFields.length > 0
                ? mappedFields.map((field) => {
                    return {
                      local_field:
                        field.local_field === null ? 0 : field.local_field.id,
                      integration_field: field.integration_field.id,
                    };
                  })
                : [
                    {
                      local_field: "Primary Field",
                      integration_field: "",
                    },
                  ]
            }
          >
            {(fields, { add, remove }) => (
              <>
                {fields.map(({ key, name, ...restField }) => (
                  <span key={key}>
                    <Row gutter={16}>
                      <Col span={11}>
                        <Form.Item
                          {...restField}
                          name={[name, "local_field"]}
                          rules={[
                            {
                              required: true,
                              message: "Missing local field",
                            },
                          ]}
                        >
                          {key === 0 ? (
                            <Select disabled>
                              <Option value={0}>Primary Field</Option>
                            </Select>
                          ) : (
                            <Select
                              options={localFields.map((field) => {
                                return {
                                  value: field.id,
                                  label: field.name,
                                };
                              })}
                            />
                          )}
                        </Form.Item>
                      </Col>
                      <Col span={11}>
                        <Form.Item
                          {...restField}
                          name={[name, "integration_field"]}
                          rules={[
                            {
                              required: true,
                              message: "Missing Zoho Books field",
                            },
                          ]}
                        >
                          <Select
                            options={integrationFields.map((field) => {
                              return {
                                value: field.id,
                                label: field.name,
                              };
                            })}
                          />
                        </Form.Item>
                      </Col>
                      <Col span={2}>
                        {key === 0 ? (
                          <></>
                        ) : (
                          <MinusCircleOutlined onClick={() => remove(name)} />
                        )}
                      </Col>
                    </Row>
                  </span>
                ))}
                <Form.Item>
                  <Button
                    type="dashed"
                    onClick={() => add()}
                    block
                    icon={<PlusOutlined />}
                  >
                    Add field
                  </Button>
                </Form.Item>
              </>
            )}
          </Form.List>
        </Form>
      </Card>
    </Space>
  );
};

export default ZohoBooksSettings;
