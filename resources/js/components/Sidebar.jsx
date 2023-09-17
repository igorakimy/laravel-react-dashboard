import { useState } from "react";
import { Layout, Menu } from "antd";
import { RocketOutlined, TeamOutlined } from "@ant-design/icons";

import theme from "../theme";

// const drawerWidth = 250;
//
// const DrawerHeader = styled('div')(({ theme }) => ({
//   display: 'flex',
//   alignItems: 'center',
//   padding: theme.spacing(0, 1),
//   ...theme.mixins.toolbar,
//   justifyContent: 'flex-end',
// }))

export default function Sidebar() {
  const [open, setOpen] = useState(false);
  const [collapsed, setCollapsed] = useState(false);

  const handleDrawerOpen = () => {
    setOpen(true);
  };

  const handleDrawerClose = () => {
    setOpen(false);
  };

  return (
    <Layout.Sider trigger={null} collapsible collapsed={collapsed}>
      <div className="demo-logo-vertical" />
      <Menu
        theme="dark"
        mode="inline"
        defaultSelectedKeys={["1"]}
        items={[
          {
            key: "1",
            icon: <RocketOutlined />,
            label: "Dashboard",
          },
          {
            key: "2",
            icon: <TeamOutlined />,
            label: "Users",
          },
        ]}
      />
    </Layout.Sider>
    // <Drawer sx={{
    //   width: drawerWidth,
    //   flexShrink: 0,
    //   '& .MuiDrawer-paper': {
    //     width: drawerWidth,
    //     boxSizing: 'border-box',
    //   }
    // }}
    // variant='persistent'
    // anchor='left'
    // open={open}>
    //   <DrawerHeader>
    //     <IconButton onClick={handleDrawerClose}>
    //       {theme.direction === 'ltr' ? <ChevronLeftIcon /> : <ChevronRightIcon />}
    //     </IconButton>
    //   </DrawerHeader>
    //   <Divider />
    //   <List>
    //     <ListItemButton>
    //       <ListItemIcon>
    //         <RocketLaunchIcon />
    //       </ListItemIcon>
    //       <ListItemText primary="Dashboard"/>
    //     </ListItemButton>
    //
    //     <ListItemButton>
    //       <ListItemIcon>
    //         <GroupsIcon />
    //       </ListItemIcon>
    //       <ListItemText primary="Users"/>
    //     </ListItemButton>
    //   </List>
    // </Drawer>
  );
}
