import React from "react";
import { Link, useLocation } from "react-router-dom";
import { Menu } from "antd";
import {
  RocketOutlined,
  TeamOutlined,
  ShoppingOutlined,
} from "@ant-design/icons";

const SidebarMenu = () => {
  const location = useLocation();

  function getItem(label, key, path, icon, items) {
    return {
      key,
      path,
      icon,
      items,
      label,
    };
  }

  const items = [
    getItem("Dashboard", "1", "/", <RocketOutlined />),
    getItem("Access", "2", "accessSubmenu", <TeamOutlined />, [
      getItem("Users", "3", "/users"),
      getItem("Roles", "4", "/roles"),
    ]),
    getItem("Inventory", "5", "inventorySubmenu", <ShoppingOutlined />, [
      getItem("Products", "6", "/products"),
      getItem("Categories", "7", "/categories"),
    ]),
  ];

  return (
    <Menu
      theme="dark"
      mode="inline"
      selectedKeys={[location.pathname]}
      defaultSelectedKeys={[location.pathname]}
      defaultOpenKeys={[location.pathname, "accessSubmenu", "inventorySubmenu"]}
    >
      {items.map((item) =>
        item.items ? (
          <Menu.SubMenu key={item.path} icon={item.icon} title={item.label}>
            {item.items.map((child) => (
              <Menu.Item style={{ userSelect: "none" }} key={child.path}>
                <Link to={child.path}>{child.label}</Link>
              </Menu.Item>
            ))}
          </Menu.SubMenu>
        ) : (
          <Menu.Item icon={item.icon} key={item.path}>
            <Link to={item.path}>{item.label}</Link>
          </Menu.Item>
        ),
      )}
    </Menu>
  );
};

export default SidebarMenu;
