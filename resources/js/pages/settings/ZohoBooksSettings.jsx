import { Button, Card, Form, Input, message, Select, Space } from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";

const ZohoBooksSettings = () => {
  const [externalPopup, setExternalPopup] = useState(null);
  const [authUrl, setAuthUrl] = useState("");
  const [refreshToken, setRefreshToken] = useState(
    localStorage.getItem("zoho_books_refresh_token"),
  );
  const [expireToken, setExpireToken] = useState(
    localStorage.getItem("zoho_books_expire_token"),
  );
  const [settings, setSettings] = useState({});
  const [messageApi, contextHolder] = message.useMessage();

  const [form] = Form.useForm();

  useEffect(() => {
    getAuthUrl();
    getSettings();
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

  return (
    <Card
      type="inner"
      title="Zoho Books"
      extra={
        <Space>
          {refreshToken && Date.now() / 1000 < expireToken ? (
            <Button
              onClick={handleDisconnectZohoBooks}
              type="primary"
              style={{
                backgroundColor: "#ff4d4f",
              }}
            >
              Disconnect
            </Button>
          ) : (
            <Button onClick={handleConnectZohoBooks} type="primary">
              Connect
            </Button>
          )}
          <Button
            type="primary"
            onClick={handleSaveZohoBooksConfig}
            style={{ backgroundColor: "green" }}
          >
            Save
          </Button>
        </Space>
      }
    >
      {contextHolder}

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
  );
};

export default ZohoBooksSettings;
