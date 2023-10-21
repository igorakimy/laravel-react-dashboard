import { Button, Result } from "antd";
import { Link } from "react-router-dom";

export default function Error403() {
  return (
    <Result
      status="403"
      title="403"
      subTitle="Access denied."
      extra={
        <Link to="/">
          <Button type="primary">Back Home</Button>
        </Link>
      }
    />
  );
}
