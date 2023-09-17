import { Button, Dropdown } from "antd";
import { UserOutlined } from "@ant-design/icons";
import { Link } from "react-router-dom";

const UserDropdown = ({ logout }) => {
  const items = [
    {
      key: "1",
      label: (
        <Link to="#" onClick={logout}>
          Logout
        </Link>
      ),
    },
  ];

  return (
    <Dropdown menu={{ items }} placement="bottomRight" trigger={["click"]}>
      <Button
        type="text"
        icon={<UserOutlined />}
        style={{
          float: "right",
          fontSize: "16px",
          width: 64,
          height: 64,
        }}
      />
    </Dropdown>
  );
};

export default UserDropdown;
