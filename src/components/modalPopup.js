import React from "react";
import { useState } from "react";
import { __ } from "@wordpress/i18n";
import { useBlockProps, RichText } from "@wordpress/block-editor";

const modalPopup = ({ data, index, props, setSettingModel, settingModel }) => {
	const { attributes, setAttributes } = props;
	console.log(attributes, "modal popup");

	const [isToggled, toggle] = useState(false);
	const placeholderFields = ['text','email', 'textarea', 'number', 'url']

	const reuiredCheckHandlar = (value, index) => {
		toggle(!isToggled);
		const requiredFormData = [...attributes.FormData];
		requiredFormData[index].required = value.target.checked;
		setAttributes({ FormData: requiredFormData });
	};

	const addlabelhandlar = (label, index) => {
		const labelFormData = [...attributes.FormData];
		labelFormData[index].label = label;
		setAttributes({ FormData: labelFormData });
	};

	const addClasshandlar = (classAttribute, index) => {
		const classAttributeFormData = [...attributes.FormData];
		classAttributeFormData[index].class = classAttribute;
		setAttributes({ FormData: classAttributeFormData });
	};

	const placeholderhandlar = (placeholder, index) => {
		const placeholderFormData = [...attributes.FormData];
		placeholderFormData[index].placeholder = placeholder;
		setAttributes({ FormData: placeholderFormData });
	};

	const addnamehandlar = (name, index) => {
		const nameFormData = [...attributes.FormData];
		nameFormData[index].name = name;
		setAttributes({ FormData: nameFormData });
	};

	const addMinValueHandlar = (min, index) => {
		const minFormData = [...attributes.FormData];
		minFormData[index].min = min.target.value;
		setAttributes({ FormData: minFormData });
	};

	const addMaxValueHandlar = (max, index) => {
		const maxFormData = [...attributes.FormData];
		maxFormData[index].max = max.target.value;
		setAttributes({ FormData: maxFormData });
	};

	const addOptionsHandlar = (options, index) => {
		const optionsFormData = [...attributes.FormData];
		optionsFormData[index].options = options.target.value;
		setAttributes({ FormData: optionsFormData });
	};

	const addmultipleHandlar = (value, index) => {
		const multipleFormData = [...attributes.FormData];
		multipleFormData[index].multiple = value.target.checked;
		setAttributes({ FormData: multipleFormData });
	};

	return (
		<div className="modal">
			<div
				className="modal-wrapper"
				style={{
					padding: "50px",
					backgroundColor: "whitesmoke",
					position: "absolute",
					top: "50%",
					left: "50%",
					transform: "translate(-50%, -50%)",
					maxWidth: "500px",
					width: "100%",
					zIndex: "999",
				}}
			>
				<h3 style={{ marginBottom: "0px" }}>Field settings</h3>
				<span>
					<button onClick={() => setSettingModel(!settingModel)}>X</button>
				</span>
				<div>
					<label>Field type:</label>
					<input
						type="checkbox"
						onClick={(value) => reuiredCheckHandlar(value, index)}
						checked={ data.required }
						value={data.required}
					/>
					<label>Required field</label>
				</div>
				<div>
					<label>label :</label>
					<RichText
						{...useBlockProps}
						tagName="span"
						value={data.label}
						style={{ margin: "0px" }}
						onChange={(label) => addlabelhandlar(label, index)}
						placeholder={__(`Enter ${data.type} field label name`)}
					/>
				</div>
				{ placeholderFields.includes(data.type) &&
				<div>
					<label>placeholder </label>
					<RichText
						{...useBlockProps}
						tagName="span"
						value={data.placeholder}
						style={{ margin: "0px" }}
						onChange={(placeholder) => placeholderhandlar(placeholder, index)}
						placeholder={__(`Enter ${data.type} field label name`)}
					/>
				</div>
				}
				<div>
					<label>Class attribute </label>
					<RichText
						{...useBlockProps}
						tagName="span"
						value={data.class}
						style={{ margin: "0px" }}
						onChange={(classAttribute) =>
							addClasshandlar(classAttribute, index)
						}
						placeholder={__(`Enter ${data.type} field class name`)}
					/>
				</div>
				<div>
					<label>Name </label>
					<RichText
						{...useBlockProps}
						tagName="span"
						value={data.name}
						style={{ margin: "0px" }}
						onChange={(name) => addnamehandlar(name, index)}
						placeholder={__(`Enter ${data.type} field name`)}
					/>
				</div>
				{(data.type == "number" || data.type == "date") && (
					<div>
						<label>Range </label>
						<span>min</span>
						<input
							type={data.type}
							value={data.min}
							onChange={(min) => addMinValueHandlar(min, index)}
						></input>
						<span>max</span>
						<input
							type={data.type}
							value={data.max}
							onChange={(max) => addMaxValueHandlar(max, index)}
						></input>
					</div>
				)}
				{data.type == "drop-down" && (
					<div>
						<label>allow multiple select </label>
						<input
							type="checkbox"
							onChange={(value) => addmultipleHandlar(value, index)}
						/>
					</div>
				)}
				{(data.type == "drop-down" ||
					data.type == "checkboxes" ||
					data.type == "radio_buttons") && (
					<div>
						<label>Options </label>
						<textarea
							value={data.options}
							onChange={(options) => addOptionsHandlar(options, index)}
						></textarea>
						<span>Enter each option comma separated</span>
					</div>
				)}
			</div>
		</div>
	);
};

export default modalPopup;
