import {
  Button,
  Card,
  message,
  Popconfirm,
  Space,
  Table,
  Tag,
  Tooltip,
} from "antd";
import { useStateContext } from "../../contexts/ContextProvider.jsx";
import { useEffect, useState } from "react";
import {
  QuestionCircleOutlined,
  SendOutlined,
  StopOutlined,
} from "@ant-design/icons";
import axiosClient from "../../axios-client.js";
import { useNavigate } from "react-router-dom";
import InvitationCreateForm from "../../components/forms/InvitationCreateForm.jsx";

const InvitationsList = () => {
  const navigate = useNavigate();
  const { can, currentUser } = useStateContext();
  const [openCreateForm, setOpenCreateForm] = useState(false);
  const [invitations, setInvitations] = useState([]);
  const [invitation, setInvitation] = useState({});
  const [loading, setLoading] = useState(false);
  const [messageApi, contextHolder] = message.useMessage();
  const [errors, setErrors] = useState({});
  const [tableParams, setTableParams] = useState({
    pagination: {
      current: 1,
      pageSize: 10,
      pageSizeOptions: ["10", "25", "50", "100", "500", "1000"],
      showQuickJumper: true,
    },
  });

  const getColumns = () => [
    {
      title: "Sender",
      dataIndex: "sender",
      sorter: true,
      render: (_, { sender }) =>
        sender.email === currentUser.email
          ? "You"
          : sender.first_name + " " + sender.last_name,
    },
    {
      title: "Invitee",
      dataIndex: "invitee",
      sorter: true,
      render: (_, { invitee }) =>
        typeof invitee === "object" ? invitee.email : invitee,
    },
    {
      title: "Allowed roles",
      dataIndex: "allowed_roles",
      sorter: true,
      render: (_, { roles }) => (
        <Space size={[0, 4]} wrap>
          {roles.map((role) => {
            return (
              <Tag color="green" key={role.name}>
                {role.name}
              </Tag>
            );
          })}
        </Space>
      ),
    },
    {
      title: "Status",
      dataIndex: "status",
      sorter: true,
      render: (_, { id, state }) => (
        <Tag
          color={
            {
              expired: "red",
              pending: "",
              accepted: "green",
              declined: "red",
              revoked: "red",
            }[state]
          }
          key={id}
        >
          {state}
        </Tag>
      ),
    },
    {
      title: "Sent At",
      dataIndex: "created_at",
      sorter: true,
    },
    {
      title: "Actions",
      align: "center",
      width: 80,
      render: (_, render) => (
        <Space size="small">
          {can("invitations.resend") && (
            <Popconfirm
              placement="topLeft"
              icon={<QuestionCircleOutlined style={{ color: "green" }} />}
              title="Are you sure to resend this invitation?"
              onConfirm={() => handleResendInvitation(render.id)}
              okText="Yes"
              cancelText="No"
            >
              <Tooltip placement="top" title="Resend">
                <Button
                  size="small"
                  icon={<SendOutlined style={{ color: "green" }} />}
                />
              </Tooltip>
            </Popconfirm>
          )}
          {can("invitations.revoke") && (
            <Popconfirm
              placement="topLeft"
              icon={<QuestionCircleOutlined style={{ color: "#ec4545" }} />}
              title="Are you sure to revoke this invitation?"
              onConfirm={() => handleRevokeInvitation(render.id)}
              okText="Yes"
              cancelText="No"
            >
              <Tooltip placement="top" title="Revoke">
                <Button
                  size="small"
                  icon={<StopOutlined style={{ color: "#ec4545" }} />}
                />
              </Tooltip>
            </Popconfirm>
          )}
        </Space>
      ),
    },
  ];

  useEffect(() => {
    if (!can("invitations.index")) {
      navigate("/forbidden");
    }

    getInvitations();
  }, [JSON.stringify(tableParams)]);

  const getInvitations = () => {
    setLoading(true);
    axiosClient
      .get("/invitations", {
        params: getTableParams(tableParams),
      })
      .then(({ data }) => {
        setInvitations(data.data);
        setLoading(false);
        setTableParams({
          ...tableParams,
          pagination: {
            ...tableParams.pagination,
            current: data.meta.current_page,
            pageSize: data.meta.per_page,
            total: data.meta.total,
            showTotal: (total, range) =>
              `${range[0]}-${range[1]} of ${total} items`,
          },
        });
      })
      .catch((err) => {
        setLoading(false);
      });
  };

  const handleTableChange = (pagination, filters, sorter) => {
    setTableParams({
      pagination,
      filters,
      ...sorter,
    });

    if (pagination.pageSize !== tableParams.pagination?.pageSize) {
      setInvitations([]);
    }
  };

  const getTableParams = (params) => ({
    ...params,
  });

  // Handle invitation creating.
  const handleInvitationCreate = (values) => {
    showLoadingMessage("Creating...");

    axiosClient
      .post("/invitations/send", values)
      .then(({ data }) => {
        setOpenCreateForm(false);
        getInvitations();
        showMessage("success", "Invitation successfully sent!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to sent invitation: " + msg);
        }
      });
  };

  const handleResendInvitation = (invitationID) => {
    showLoadingMessage("Resending...");

    axiosClient
      .post("/invitations/resend/" + invitationID)
      .then(({ data }) => {
        showMessage("success", "Invitation successfully resent!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to resent invitation: " + msg);
        }
      });

    getInvitations();
  };

  const handleRevokeInvitation = (invitationID) => {
    showLoadingMessage("Revoking...");

    axiosClient
      .post("/invitations/revoke/" + invitationID)
      .then(({ data }) => {
        showMessage("success", "Invitation successfully revoked!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to revoked invitation: " + msg);
        }
      });

    getInvitations();
  };

  const handleErrors = () => {
    setErrors(errors);
  };

  const showLoadingMessage = (content) => {
    messageApi.open({
      key: "updatable",
      type: "loading",
      content: content,
    });
  };

  const showMessage = (type, content) => {
    messageApi.open({
      key: "updatable",
      type: type,
      content: content,
    });
  };

  return (
    <Card
      type="inner"
      title="Invitations"
      extra={
        can("invitations.send") ? (
          <Button
            size="small"
            type="primary"
            onClick={() => {
              setOpenCreateForm(true);
            }}
          >
            New
          </Button>
        ) : null
      }
    >
      {contextHolder}

      <Table
        rowSelection={{
          type: "checkbox",
        }}
        columns={getColumns()}
        rowKey={(record) => record.id}
        dataSource={invitations}
        pagination={tableParams.pagination}
        loading={loading}
        onChange={handleTableChange}
        scroll={{ y: 450, x: 500 }}
        size="small"
      />

      <InvitationCreateForm
        open={openCreateForm}
        onCreate={handleInvitationCreate}
        onCancel={() => setOpenCreateForm(false)}
        errors={errors}
        onError={handleErrors}
      />
    </Card>
  );
};

export default InvitationsList;
