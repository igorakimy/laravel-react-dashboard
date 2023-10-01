import { LockOutlined, MailOutlined } from "@ant-design/icons";
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
import { useState } from "react";
import { Link } from "react-router-dom";
import axiosClient from "../../axios-client";
import { useStateContext } from "../../contexts/ContextProvider";
import LogoIcon from "../../components/icons/LogoIcon";

export default function Login() {
  const { setCurrentUser, setToken } = useStateContext();
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState("");

  const { Title } = Typography;

  const validationRules = {
    email: [
      {
        required: true,
        message: "Please input your Email",
      },
    ],
    password: [
      {
        required: true,
        message: "Please input your Password",
      },
    ],
  };

  const handleSubmit = (values) => {
    setMessage("");

    axiosClient
      .post("/login", values)
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
        backgroundImage: "url(./background.jpg)",
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
            marginTop: "2rem",
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
              Login
            </Title>

            <Form
              name="normal_login"
              className="login-form"
              initialValues={{
                remember: true,
              }}
              onFinish={handleSubmit}
            >
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
              <Form.Item>
                <Row>
                  <Col xs={{ span: 12 }} sm={{ span: 12 }}>
                    <Form.Item name="remember" valuePropName="checked" noStyle>
                      <Checkbox>Remember me</Checkbox>
                    </Form.Item>
                  </Col>
                  <Col xs={{ span: 12 }} sm={{ span: 12 }}>
                    <Link
                      className="login-form-forgot"
                      to="/users"
                      style={{
                        float: "right",
                      }}
                    >
                      Forgot password?
                    </Link>
                  </Col>
                </Row>
              </Form.Item>

              <Form.Item style={{ marginBottom: 0 }}>
                <Button
                  type="primary"
                  htmlType="submit"
                  className="login-form-button"
                  style={{
                    width: "100%",
                  }}
                >
                  Login
                </Button>
              </Form.Item>
            </Form>
          </Card>
        </Col>
      </Row>
    </main>
    // <Container component="main" maxWidth="xs">
    //   <Box
    //     sx={{
    //       marginTop: 8,
    //       display: "flex",
    //       flexDirection: "column",
    //       alignItems: "center",
    //     }}
    //   >
    //     <Typography component="h1" variant="h5">
    //       Sign In
    //     </Typography>
    //
    //     <Box component="form" onSubmit={handleSubmit} noValidate sx={{ mt: 1 }}>
    //       {message ? (
    //         <Alert
    //           icon={false}
    //           severity="error"
    //           action={
    //             <IconButton
    //               aria-label="close"
    //               color="inherit"
    //               size="small"
    //               onClick={() => {
    //                 setMessage("");
    //               }}
    //             >
    //               <CloseIcon fontSize="inherit" />
    //             </IconButton>
    //           }
    //           sx={{
    //             mt: 1,
    //             maxWidth: "100%",
    //           }}
    //         >
    //           {typeof message === "string" ? message : message.join("\n")}
    //         </Alert>
    //       ) : null}
    //
    //       <TextField
    //         margin="normal"
    //         required
    //         fullWidth
    //         id="email"
    //         label="Email Address"
    //         name="email"
    //         autoComplete="email"
    //         autoFocus
    //         error={errors && errors.email}
    //         helperText={errors && errors.email ? errors.email[0] : null}
    //       />
    //       <TextField
    //         margin="normal"
    //         required
    //         fullWidth
    //         id="password"
    //         label="Password"
    //         name="password"
    //         type="password"
    //         autoComplete="current-password"
    //         error={errors && errors.password}
    //         helperText={errors && errors.password ? errors.password[0] : null}
    //       />
    //
    //       <Grid container>
    //         <Grid item xs>
    //           <FormControlLabel
    //             control={<Checkbox value="remember" color="primary" />}
    //             label="Remember me"
    //             variant="body2"
    //             sx={{ userSelect: "none" }}
    //           />
    //         </Grid>
    //         <Grid item xs sx={{ textAlign: "right", pt: 1 }}>
    //           <Link href="/users" variant="body1">
    //             Forgot password?
    //           </Link>
    //         </Grid>
    //       </Grid>
    //
    //       <Button
    //         type="submit"
    //         fullWidth
    //         variant="contained"
    //         sx={{ mt: 3, mb: 2 }}
    //       >
    //         Sign In
    //       </Button>
    //     </Box>
    //   </Box>
    // </Container>
  );
}
