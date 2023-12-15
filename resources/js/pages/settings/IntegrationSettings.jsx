import { Card, message, Space, Tabs } from "antd";
import ZohoBooksSettings from "./ZohoBooksSettings.jsx";
import Icon from "@ant-design/icons";
import ZohoBooksLogoIcon from "../../components/icons/ZohoBooksLogoIcon.jsx";
import ZohoCrmLogoIcon from "../../components/icons/ZohoCrmLogoIcon.jsx";
import ZohoInventoryLogoIcon from "../../components/icons/ZohoInventoryLogoIcon.jsx";
import ZohoInventorySettings from "./ZohoInventorySettings.jsx";

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

  const integrations = [
    {
      label: "Zoho Books",
      icon: <ZohoBooksLogoIcon style={{ height: 12, fill: "#1677ff", marginRight: 5 }} />,
      key: "zoho_books",
      children: <ZohoBooksSettings />,
    },
    {
      label: "Zoho Inventory",
      icon: <ZohoInventoryLogoIcon style={{ height: 12, fill: "#1677ff", marginRight: 5 }} />,
      key: "zoho_inventory",
      children: <ZohoInventorySettings />,
    },
    {
      label: "Zoho CRM",
      icon: <ZohoCrmLogoIcon style={{ height: 12, fill: "#1677ff", marginRight: 5 }} />,
      key: "zoho_crm",
      children: (
        <Card type="inner" title="Connection" style={{ width: 800 }}>
          Coming soon...
        </Card>
      ),
    },
  ];

  return (
    <Tabs
      defaultActiveKey="zoho_books"
      items={integrations.map((integration, i) => {
        return {
          label: (
            <span>
              <Icon style={{ margin: 0 }} component={() => integration.icon} />
              {integration.label}
            </span>
          ),
          key: integration.key,
          children: integration.children,
        };
      })}
    />
    // <Space
    //   direction="vertical"
    //   size={16}
    //   style={{ display: "flex", width: 800 }}
    // >
    //   {contextHolder}
    //   {cards.map((card) => card.children)}
    // </Space>
  );
};

export default IntegrationSettings;
