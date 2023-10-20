import { Card, Space } from "antd";

const GeneralSettings = () => {
  return (
    <Space size={16} direction="vertical" style={{ display: "flex" }}>
      <Card type="inner" title="Config 1" style={{ width: 800 }}>
        Config 1
      </Card>
      <Card type="inner" title="Config 2" style={{ width: 800 }}>
        Config 2
      </Card>
      <Card type="inner" title="Config 3" style={{ width: 800 }}>
        Config 3
      </Card>
    </Space>
  );
};

export default GeneralSettings;
