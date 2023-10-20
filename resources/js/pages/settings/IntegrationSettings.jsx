import { Card, message, Space } from "antd";
import ZohoBooksSettings from "./ZohoBooksSettings.jsx";

const IntegrationSettings = () => {
  const [messageApi, contextHolder] = message.useMessage();

  const showMessage = (type, content) => {
    messageApi.open({
      key: "updatable",
      type: type,
      content: content,
    });
  };

  const cards = [
    {
      label: "Zoho Books",
      key: "zoho_books",
      children: <ZohoBooksSettings />,
    },
    {
      label: "Zoho CRM",
      key: "zoho_crm",
      children: (
        <Card type="inner" title="Zoho CRM">
          Coming soon...
        </Card>
      ),
    },
  ];

  return (
    <Space
      direction="vertical"
      size={16}
      style={{ display: "flex", width: 800 }}
    >
      {contextHolder}
      {cards.map((card) => card.children)}
    </Space>
  );
};

export default IntegrationSettings;
