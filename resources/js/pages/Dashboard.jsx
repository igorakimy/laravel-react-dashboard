import {
  HistoryOutlined,
  RightCircleFilled,
  SettingOutlined,
  ShoppingOutlined,
  TeamOutlined,
} from "@ant-design/icons";
import { Alert, Card, Col, Row, Space, Typography } from "antd";
import { Link } from "react-router-dom";

export default function Dashboard() {
  const { Title, Text } = Typography;

  const dashboardItems = [
    {
      path: "/users",
      title: "Users",
      icon: (
        <TeamOutlined
          style={{
            fontSize: "30px",
            color: "#1890ff",
            float: "right",
          }}
        />
      ),
    },
    {
      path: "/products",
      title: "Inventory",
      icon: (
        <ShoppingOutlined
          style={{
            fontSize: "30px",
            color: "#1890ff",
            float: "right",
          }}
        />
      ),
    },
    {
      path: "/activity",
      title: "Activity",
      icon: (
        <HistoryOutlined
          style={{
            fontSize: "30px",
            color: "#1890ff",
            float: "right",
          }}
        />
      ),
    },
    {
      path: "/settings",
      title: "Settings",
      icon: (
        <SettingOutlined
          style={{
            fontSize: "30px",
            color: "#1890ff",
            float: "right",
          }}
        />
      ),
    },
  ];

  return (
    <Space direction="vertical" size="middle" style={{ display: "flex" }}>
      <Title level={3}>Dashboard</Title>

      <Alert
        message="Welcome!"
        description="To continue working, select one of the menu items."
        type="info"
        style={{
          maxWidth: "380px",
        }}
      />

      <Row
        gutter={[
          {
            xs: 8,
            sm: 16,
            md: 24,
            lg: 32,
          },
          { xs: 8, sm: 16, md: 24, lg: 32 },
        ]}
      >
        {dashboardItems.map((item) => (
          <Col
            key={item.path}
            xs={{ span: 24 }}
            sm={{ span: 23 }}
            md={{ span: 12 }}
            lg={{ span: 6 }}
          >
            <Link to={item.path}>
              <Card type="inner" hoverable={true}>
                <Text
                  strong
                  style={{
                    fontSize: "1.125rem",
                  }}
                >
                  {item.title} <RightCircleFilled />
                </Text>
                {item.icon}
              </Card>
            </Link>
          </Col>
        ))}
      </Row>
    </Space>
  );
}
