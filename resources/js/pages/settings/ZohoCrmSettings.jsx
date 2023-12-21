import {
  Button,
  Card,
  Form,
  Input,
  message,
  Space,
} from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";

const ZohoCrmSettings = () => {
  const [externalPopup, setExternalPopup] = useState(null);
  const [authUrl, setAuthUrl] = useState("");
  const [localFields, setLocalFields] = useState([]);
  const [integrationFields, setIntegrationFields] = useState([]);
  const [mappedFields, setMappedFields] = useState([]);
  const [refreshToken, setRefreshToken] = useState(
    localStorage.getItem("zoho_crm_refresh_token"),
  );
  const [expireToken, setExpireToken] = useState(
    localStorage.getItem("zoho_crm_expire_token"),
  );
  const [settings, setSettings] = useState({});
  const [messageApi, contextHolder] = message.useMessage();

  const [form] = Form.useForm();
  const [fieldsMappingForm] = Form.useForm();

  useEffect(() => {
    getAuthUrl();
    getSettings();
    getLocalFields();
    getMappedFields("zoho-crm");
    getIntegrationFields("zoho-crm");

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
          .get("/integrations/zoho-crm/callback?code=" + code)
          .then((response) => {
            if (response.data.access_token) {
              setRefreshToken(response.data.refresh_token);
              localStorage.setItem(
                "zoho_crm_refresh_token",
                response.data.refresh_token,
              );

              const expiresIn = response.data.expires_in + Date.now() / 1000;
              setExpireToken(expiresIn);
              localStorage.setItem("zoho_crm_expire_token", expiresIn);
              console.log(response.data.expires_in);
              setExternalPopup(null);
              timer && clearInterval(timer);
              messageApi.success("Zoho CRM successfully connected!");
              getIntegrationFields("zoho-crm");
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
      .get("/integrations/zoho-crm/authenticate")
      .then((response) => {
        setAuthUrl(response.data.auth_url);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getSettings = () => {
    axiosClient
      .get("/settings/zoho-crm")
      .then((response) => {
        console.log(response.data);
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

  const handleConnectZohoCrm = (e) => {
    const width = 700;
    const height = 700;
    const left = window.screenX + (window.outerWidth - width) / 2;
    const top = window.screenY + (window.outerHeight - height) / 2.5;
    const title = `Connect To Zoho CRM`;

    const popup = window.open(
      authUrl,
      title,
      `toolbar=0,scrollbars=1,status=1,resizable=0,location=1,menuBar=0,width=${width},height=${height},left=${left},top=${top}`,
    );
    setExternalPopup(popup);
  };

  const handleDisconnectZohoCrm = () => {
    localStorage.removeItem("zoho_crm_refresh_token");
    localStorage.removeItem("zoho_crm_expire_token");
    setRefreshToken("");
    setExpireToken(null);
    messageApi.success("Zoho CRM successfully disconnected!");
  };

  const handleSaveZohoCrmConfig = (values) => {
    form.validateFields().then((values) => {
      axiosClient
        .put("/settings/zoho-crm", values)
        .then((response) => {
          getSettings();
          getAuthUrl();
          messageApi.success("Zoho CRM settings successfully saved!");
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
          .post("/integration-fields/mapping/zoho-crm", values)
          .then((response) => {
            messageApi.success("Zoho CRM fields successfully mapped!");
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
                onClick={handleDisconnectZohoCrm}
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
                onClick={handleConnectZohoCrm}
                type="primary"
              >
                Connect
              </Button>
            )}
            <Button
              type="primary"
              size="small"
              onClick={handleSaveZohoCrmConfig}
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
    </Space>
  );
};

export default ZohoCrmSettings;
