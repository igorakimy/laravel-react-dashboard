import React, { useContext, useEffect, useRef, useState } from "react";
import {
  Button,
  Form,
  Input,
  InputNumber,
  Popconfirm,
  Select,
  Space,
  Switch,
  Table,
  Typography,
} from "antd";
import {
  CheckOutlined,
  CloseOutlined,
  DeleteOutlined,
  EditOutlined,
  QuestionCircleOutlined,
} from "@ant-design/icons";
import axiosClient from "../../axios-client.js";

// const EditableContext = React.createContext(null);

// const EditableRow = ({ index, ...props }) => {
//
//
//   return (
//     <Form form={form} component={false}>
//       <EditableContext.Provider value={{ form, isEditing, edit, cancel }}>
//         <tr {...props} />
//       </EditableContext.Provider>
//     </Form>
//   );
// };

const EditableCell = ({
  title,
  editing,
  children,
  dataIndex,
  record,
  cellInputType,
  handleSave,
  ...restProps
}) => {
  let timeout;
  let currentValue;
  const fetchProducts = (value, callback) => {
    if (timeout) {
      clearTimeout(timeout);
      timeout = null;
    }
    currentValue = value;
    const getProducts = () => {
      axiosClient
        .get(
          "/products?kind=select&operator=OR&filters[name][0]=" +
            value +
            "&filters[sku][0]=" +
            value,
        )
        .then((response) => {
          const d = response.data;
          if (currentValue === value) {
            const data = d.map((item) => ({
              value: item.name,
              text: item.name,
            }));
            callback(data);
          }
        });
    };
    if (value) {
      timeout = setTimeout(getProducts, 300);
    } else {
      callback([]);
    }
  };

  return (
    <td {...restProps}>
      {editing ? (
        <Form.Item
          style={{
            margin: 0,
          }}
          name={dataIndex}
          rules={[
            {
              required: true,
              message: `${title} is required.`,
            },
          ]}
          valuePropName={cellInputType === "switch" ? "checked" : "value"}
        >
          {cellInputType === "number" && <InputNumber step="0.1" />}
          {cellInputType === "input" && <Input />}
          {cellInputType === "select" && (
            <SearchInput
              placeholder="Search product by name or sku"
              onSearch={fetchProducts}
            />
          )}
          {cellInputType === "switch" && (
            <Switch checkedChildren="Yes" unCheckedChildren="No" size="small" />
          )}
        </Form.Item>
      ) : cellInputType === "switch" ? (
        <Switch
          disabled
          checkedChildren="Yes"
          unCheckedChildren="No"
          size="small"
          defaultChecked={record.visible}
        />
      ) : (
        children
      )}
    </td>
  );
};

const ComponentsTable = ({ product, open }) => {
  const [dataSource, setDataSource] = useState([
    {
      key: "0",
      product: "Product 1",
      quantity: 17,
      cost_price: "129",
      selling_price: "153",
      visible: true,
    },
    {
      key: "1",
      product: "Product 2",
      quantity: 10,
      cost_price: "150",
      selling_price: "200",
      visible: true,
    },
  ]);
  const [count, setCount] = useState(2);
  const [form] = Form.useForm();
  const [editingKey, setEditingKey] = useState("");
  const isEditing = (record) => record.key === editingKey;

  const edit = (record) => {
    form.setFieldsValue({
      name: "",
      age: "",
      address: "",
      ...record,
    });
    setEditingKey(record.key);
  };

  const save = async (key) => {
    try {
      const row = await form.validateFields();
      const newData = [...dataSource];
      const index = newData.findIndex((item) => key === item.key);
      if (index > -1) {
        const item = newData[index];
        newData.splice(index, 1, {
          ...item,
          ...row,
        });
        setDataSource(newData);
        setEditingKey("");
      } else {
        newData.push(row);
        setDataSource(newData);
        setEditingKey("");
      }
    } catch (errInfo) {
      console.log("Validate Failed:", errInfo);
    }
  };

  const cancel = () => {
    setEditingKey("");
  };

  const handleDelete = (key) => {
    const newData = dataSource.filter((item) => item.key !== key);
    setDataSource(newData);
  };

  const defaultColumns = [
    {
      title: "Product",
      dataIndex: "product",
      width: "30%",
      editable: true,
      fieldType: "select",
    },
    {
      title: "Quantity",
      dataIndex: "quantity",
      width: "14%",
      editable: true,
      fieldType: "number",
    },
    {
      title: "Cost Price",
      dataIndex: "cost_price",
      fieldType: "input",
    },
    {
      title: "Selling Price",
      dataIndex: "selling_price",
      fieldType: "input",
    },
    {
      title: "Visible",
      dataIndex: "visible",
      editable: true,
      fieldType: "switch",
    },
    {
      title: "Actions",
      dataIndex: "actions",
      render: (_, record) => {
        console.log(record);
        const editable = isEditing(record);

        return editable ? (
          <Space size={[16, 0]}>
            <CheckOutlined
              style={{
                color: "green",
              }}
              onClick={() => save(record.key)}
            >
              Save
            </CheckOutlined>
            <CloseOutlined
              style={{
                color: "red",
              }}
              onClick={cancel}
            />
          </Space>
        ) : (
          <Space size={[16, 0]}>
            <EditOutlined
              disabled={editingKey !== ""}
              onClick={() => edit(record)}
              style={{
                color: "#456cec",
              }}
              tooltip="Edit"
            >
              Edit
            </EditOutlined>
            <Popconfirm
              icon={
                <QuestionCircleOutlined
                  style={{
                    color: "red",
                  }}
                />
              }
              title="Sure to delete?"
              onConfirm={() => handleDelete(record.key)}
            >
              <DeleteOutlined
                style={{
                  color: "red",
                }}
              />
            </Popconfirm>
          </Space>
        );
      },
      fieldType: null,
    },
  ];
  const handleAdd = () => {
    const newData = {
      key: count,
      product: ``,
      quantity: "1",
      selling_price: "0.00",
      cost_price: "0.00",
      visible: true,
    };
    setDataSource([...dataSource, newData]);
    setCount(count + 1);
  };

  const components = {
    body: {
      cell: EditableCell,
    },
  };
  const columns = defaultColumns.map((col) => {
    if (!col.editable) {
      return col;
    }
    return {
      ...col,
      onCell: (record) => ({
        record,
        cellInputType: col.fieldType,
        editing: isEditing(record),
        dataIndex: col.dataIndex,
        title: col.title,
      }),
    };
  });
  return (
    open && (
      <Form form={form} component={false}>
        <Table
          components={components}
          rowClassName="editable-row"
          bordered
          dataSource={dataSource}
          columns={columns}
          pagination={false}
          style={{
            marginBottom: 16,
            marginTop: 16,
          }}
        />
        <Button
          onClick={handleAdd}
          type="default"
          size="small"
          style={{
            marginBottom: 16,
          }}
        >
          + Add Product
        </Button>
      </Form>
    )
  );
};
export default ComponentsTable;
