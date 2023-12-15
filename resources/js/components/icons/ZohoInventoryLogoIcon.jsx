import Icon from "@ant-design/icons";

const ZohoInventoryLogoSvg = (props) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    id="Layer_1"
    data-name="Layer 1"
    viewBox="0 0 1024 1024"
    style={{ height: 15, fill: props.fill }}
    className="icon icon-xlg app-logo">
    <g className="cls-1">
      <path
        d="M264 866a30 30 0 01-30-25L105 106a36 36 0 00-26-28L22 60A30 30 0 1139 2l58 18a96 96 0 0167 76l129 735a30 30 0 01-24 34 30 30 0 01-5 1z"
        className="cls-2"></path>
    </g>
    <g className="cls-1">
      <path
        d="M406 966a30 30 0 01-5-60l532-94a36 36 0 10-12-72l-457 81a96 96 0 01-112-78l-81-457a96 96 0 0178-112l458-81a96 96 0 01111 78l75 424a30 30 0 01-59 10l-75-423a36 36 0 00-42-30l-457 81a36 36 0 00-30 42l81 457a36 36 0 0042 30l457-81a96 96 0 1134 190l-532 94a30 30 0 01-6 1z"
        className="cls-2"></path>
    </g>
    <path d="M527 382a30 30 0 01-5-60l173-30a30 30 0 0111 59l-174 30a30 30 0 01-5 1z" className="cls-1"></path>
    <g className="cls-1">
      <path d="M322 1023a120 120 0 1121-2 121 121 0 01-21 2zm1-179a60 60 0 00-11 0 59 59 0 1011 0z"
            className="cls-2"></path>
    </g>
  </svg>
);

const ZohoInventoryLogoIcon = (props) => (
  <Icon component={ZohoInventoryLogoSvg} {...props} />
);

export default ZohoInventoryLogoIcon;
