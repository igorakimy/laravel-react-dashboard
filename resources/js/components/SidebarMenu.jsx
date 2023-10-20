import React from "react";
import { Link, useLocation } from "react-router-dom";
import { Menu } from "antd";
import {
  TeamOutlined,
  ShoppingOutlined,
  DashboardOutlined,
  SettingOutlined,
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
    getItem("Dashboard", "1", "/", <DashboardOutlined />),
    getItem("Access", "2", "accessSubmenu", <TeamOutlined />, [
      getItem("Users", "3", "/users"),
      getItem("Roles", "4", "/roles"),
      getItem("Invitations", "5", "/invitations"),
    ]),
    getItem("Inventory", "6", "inventorySubmenu", <ShoppingOutlined />, [
      getItem("Products", "7", "/products"),
      getItem("Categories", "8", "/categories"),
    ]),
    getItem("Settings", "9", "/settings", <SettingOutlined />),
  ];

  return (
    <Menu
      theme="dark"
      mode="inline"
      selectedKeys={[location.pathname]}
      defaultSelectedKeys={[location.pathname]}
      defaultOpenKeys={[
        location.pathname,
        "accessSubmenu",
        "inventorySubmenu",
        "settingsSubmenu",
      ]}
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
