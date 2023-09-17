import { Button, Card, Divider, Table } from "antd";

const UserCreate = () => {
  return (
    <Card type="inner" title="Create New User">
      <Divider
        style={{
          margin: "16px auto",
        }}
      />
      <Button size="small" type="primary">
        Save
      </Button>
    </Card>
  );
};

export default UserCreate;
