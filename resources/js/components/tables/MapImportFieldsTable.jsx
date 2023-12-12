import {Form, Input, Select, Table} from "antd";

const MapImportFieldsTable = ({mappingForm, fields, headers}) => {
  let columns = [];

  const filterOption = (input, option) =>
    (option?.label ?? "").toLowerCase().includes(input.toLowerCase());

  const getMappingDataSource = () => {
    return fields.map((field, i) => {
      return {
        key: i,
        local_field: field.name,
        local_field_id: field.id,
        local_field_required: field.validations.required,
        import_header: null,
      };
    });
  };

  columns.push({
      title: "Inventory Field",
      dataIndex: "local_field",
      key: "local_field",
      render: (item, record, index) => (
        <>
          <Form.Item
            hidden={true}
            initialValue={record.local_field_id}
            name={['mapping', index, 'local_field']}
            style={{
              margin: 0,
            }}
            noStyle
          >
            <Input key={index}/>
          </Form.Item>
          <span className={`inventory-map-field-${record.local_field_required && 'required'}`}>
            {item}
          </span>

        </>
      ),
    },
    {
      title: "Import Header",
      dataIndex: "import_header",
      key: "import_header",
      render: (_, record, index) => (
        <Form.Item
          initialValue={null}
          name={['mapping', index, 'import_header']}
          style={{
            margin: 0,
          }}
          noStyle
        >
          <Select
            showSearch
            allowClear
            placeholder="Select a header"
            optionFilterProp="children"
            style={{
              width: "100%",
            }}
            // onChange={onChange}
            // onSearch={onSearch}
            filterOption={filterOption}
            options={headers?.map((option) => {
              return {
                label: option,
                value: option,
              };
            })}
          />
        </Form.Item>
      ),
    });

  return (
    <Form form={mappingForm} onValuesChange={(changedValues, values) => {}}>
      <Table
        size="small"
        pagination={false}
        dataSource={getMappingDataSource()}
        columns={columns}
        style={{
          marginTop: 24,
        }}
      />
    </Form>
  );
}

export default MapImportFieldsTable;
