import React, { useState } from "react";
import { __ } from "@wordpress/i18n";
import { useBlockProps, RichText } from "@wordpress/block-editor";

const mailBodyField = ({ type, element, key, props }) => {
	const { attributes, setAttributes } = props;

	const mailBodyTextHandler = (newValue, key, type) => {
		if (type === "mail") {
			const mailBodyData = [...attributes.mailBody];
			mailBodyData[key].text = newValue;
			setAttributes({ mailBody: mailBodyData });
		} else if (type === "mail2") {
			const mail2BodyData = [...attributes.mail2Body];
			mail2BodyData[key].text = newValue;
			setAttributes({ mail2Body: mail2BodyData });
		}
	};

	return (
		<div>
			{element.label == "Message Body" ? (
				<div>
					<label>{element.label}</label>
					<textarea
						className="mailBodyField"
						value={element.text}
						onChange={(newValue) =>
							mailBodyTextHandler(newValue.target.value, key, type)
						}
					></textarea>
				</div>
			) : (
				<div>
					<label>{element.label}</label>
					<RichText
						{...useBlockProps}
						className="mailBodyField"
						tagName="span"
						value={element.text}
						style={{ margin: "0px" }}
						onChange={(newValue) => mailBodyTextHandler(newValue, key, type)}
					/>
				</div>
			)}
		</div>
	);
};

export default mailBodyField;
