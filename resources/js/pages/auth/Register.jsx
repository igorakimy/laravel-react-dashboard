import { LockOutlined, MailOutlined, UserOutlined } from "@ant-design/icons";
import {
  Alert,
  Button,
  Card,
  Checkbox,
  Col,
  Form,
  Input,
  Row,
  Typography,
} from "antd";
import { useEffect, useState } from "react";
import { Link, Navigate, useParams } from "react-router-dom";
import axiosClient from "../../axios-client";
import { useStateContext } from "../../contexts/ContextProvider";
import LogoIcon from "../../components/icons/LogoIcon";

export default function Register() {
  const params = useParams();
  const { setCurrentUser, setToken } = useStateContext();
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState("");
  const [invitation, setInvitation] = useState({});

  const { Title } = Typography;

  const validationRules = {
    first_name: [
      {
        required: true,
        message: "Please input your Firstname",
      },
    ],
    last_name: [
      {
        required: true,
        message: "Please input your Lastname",
      },
    ],
    email: [
      {
        required: true,
        message: "Please input your Email",
      },
      {
        type: "email",
        message: "Please input a valid Email",
      },
    ],
    password: [
      {
        required: true,
        message: "Please input your Password",
      },
    ],
    password_confirmation: [
      {
        required: true,
        message: "Please repeat your Password again",
      },
    ],
  };

  useEffect(() => {
    // getInvitation();
  }, []);

  const getInvitation = () => {
    axios
      .post("/invitation-token/" + params.token)
      .then(({ data }) => {
        console.log(params.token, data.token);
      })
      .catch((err) => {
        setErrors({});

        const resp = err.response;
        if (resp && resp.status === 422) {
          setErrors(resp.data.errors);
        }
        setMessage(resp.data.message);
      });
  };

  const handleSubmit = (values) => {
    setMessage("");

    axiosClient
      .post("/register/" + params.token, values)
      .then(({ data }) => {
        setCurrentUser(data.user);
        setToken(data.token);
      })
      .catch((err) => {
        setErrors({});

        const resp = err.response;
        if (resp && resp.status === 422) {
          setErrors(resp.data.errors);
        }
        setMessage(resp.data.message);
        console.log(resp.data.message);
      });
  };

  return (
    <main
      style={{
        backgroundImage: "url(.././background.jpg)",
        backgroundPosition: "0% 50%",
        backgroundSize: "cover",
        height: "100%",
      }}
    >
      <Row style={{ backgroundColor: "rgb(51 51 51 / 83%)", height: "100%" }}>
        <Col
          xs={{
            span: 22,
            offset: 1,
          }}
          sm={{
            span: 12,
            offset: 5,
          }}
          md={{
            span: 10,
            offset: 6,
          }}
          lg={{
            span: 6,
            offset: 8,
          }}
          style={{
            marginTop: "10rem",
          }}
        >
          {message ? (
            <Alert
              message={
                typeof message === "string" ? message : message.join("\n")
              }
              type="error"
              closable
              style={{
                marginBottom: "1rem",
              }}
            />
          ) : null}

          <Card>
            <div style={{ textAlign: "center" }}>
              <LogoIcon style={{ color: "#6e6e6e" }} />
            </div>

            <Title level={4} style={{ textAlign: "center" }}>
              Registration
            </Title>

            <Form
              name="normal_register"
              className="register-form"
              onFinish={handleSubmit}
            >
              <Form.Item name="first_name" rules={validationRules.first_name}>
                <Input
                  prefix={<UserOutlined className="site-form-item-icon" />}
                  type="first_name"
                  placeholder="First Name"
                />
              </Form.Item>
              <Form.Item name="last_name" rules={validationRules.last_name}>
                <Input
                  prefix={<UserOutlined className="site-form-item-icon" />}
                  type="last_name"
                  placeholder="Last Name"
                />
              </Form.Item>
              <Form.Item name="email" rules={validationRules.email}>
                <Input
                  prefix={<MailOutlined className="site-form-item-icon" />}
                  type="email"
                  placeholder="Email"
                />
              </Form.Item>
              <Form.Item name="password" rules={validationRules["password"]}>
                <Input.Password
                  prefix={<LockOutlined className="site-form-item-icon" />}
                  placeholder="Password"
                />
              </Form.Item>

              <Form.Item
                name="password_confirmation"
                rules={validationRules["password_confirmation"]}
              >
                <Input.Password
                  prefix={<LockOutlined className="site-form-item-icon" />}
                  placeholder="Password Confirm"
                />
              </Form.Item>

              <Form.Item style={{ marginBottom: 0 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  className="register-form-button"
                  style={{
                    width: "100%",
                  }}
                >
                  Register
                </Button>
              </Form.Item>
            </Form>
          </Card>
        </Col>
      </Row>
    </main>
  );
}
