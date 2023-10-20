import { Space, Tabs, Typography } from "antd";
import GeneralSettings from "./GeneralSettings.jsx";
import IntegrationSettings from "./IntegrationSettings.jsx";

const Settings = () => {
  const { Title } = Typography;
  const settingsTabs = [
    {
      label: "General",
      key: "1",
      children: <GeneralSettings />,
    },
    {
      label: "Integrations",
      key: "2",
      children: <IntegrationSettings />,
    },
  ];

  return (
    <Space direction="vertical" size="middle" style={{ display: "flex" }}>
      <Title level={3}>Settings</Title>

      <Tabs tabPosition="left" items={settingsTabs} />
    </Space>
  );
};

export default Settings;
