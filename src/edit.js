import { __ } from "@wordpress/i18n";
import "./editor.scss";
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from "@wordpress/block-editor";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import {
	PanelBody,
	TextControl,
	SelectControl,
	Button,
	CheckboxControl,
	RadioControl,
	PanelRow,
} from "@wordpress/components";
import { useState } from "react";
import ModalPopup from "./components/modalPopup";
import EditMessages from "./components/editMessages";
import MailBody from "./components/mailBody";

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const postId = wp.data.select("core/editor").getCurrentPostId();
	const [editModelKey, setEditModelKey] = useState(0);
	const [settingModel, setSettingModel] = useState(false);
	const [showMessageModel, setShowMessageModel] = useState(false);
	const [showMailBody, setShowMailBody] = useState(false);
	const [showReCaptcha, setShowReCaptcha] = useState(false);
	let afterRemoveFormData = [];

	setAttributes({ postId });
	console.log(attributes);
	let FormData = [];
	let Field = {
		label: "",
		required: false,
		type: "",
		class: "",
		placeholder: "",
		name: "",
		min: "",
		max: "",
		options: "",
		multiple: false,
	};

	const AddFieldHandlar = (type) => {
		const newField = Field;
		newField.type = type;
		FormData = [...attributes.FormData];
		FormData.push(newField);
		setAttributes({ FormData });
	};

	const removeFieldHandlar = (key) => {
		afterRemoveFormData = attributes.FormData.splice(key, 1);
		setAttributes({ FormData: [...attributes.FormData] });
	};

	const addActionHandlar = (action, key) => {
		const actionFormData = [...attributes.FormData];
		actionFormData[key].action = action;
		setAttributes({ FormData: actionFormData });
	};

	const editClickHandlar = (key) => {
		setSettingModel(!settingModel);
		setEditModelKey(key);
	};

	const onDragEnd = (result) => {
		console.log(result, "onDragEnd result");
		if (!!result.destination) {
			const sampleArray = [...attributes.FormData];
			const newArray = sampleArray.splice(result.source.index, 1);
			sampleArray.splice(result.destination.index, 0, newArray[0]);
			setAttributes({ FormData: sampleArray });
		}
	};

	return (
		<div {...useBlockProps()}>
			{
				<div>
					<>
						<InspectorControls>
							<PanelBody title="Custome Fields">
								<p>{__("You can edit the form template here.")}</p>
								<div>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("text")}
									>
										{__("Text")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("email")}
									>
										{__("Email")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("textarea")}
									>
										{__("Textarea")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("number")}
									>
										{__("Number")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("url")}
									>
										{__("URL")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("date")}
									>
										{__("Date")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("drop-down")}
									>
										{__("Drop-down")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("checkboxes")}
									>
										{__("Checkboxes")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("radio_buttons")}
									>
										{__("Radio buttons")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("file")}
									>
										{__("File")}
									</Button>
									<Button
										className="addFieldButton"
										onClick={() => AddFieldHandlar("action")}
									>
										{__("Custom action")}
									</Button>
								</div>
							</PanelBody>
							<PanelBody title="Submit button setting">
								<PanelRow>{__("You can edit submit button Text")}</PanelRow>
								<input
									style={{
										border: "1px solid black",
										padding: "3px",
									}}
									type="text"
									onChange={(newText) =>
										setAttributes({ buttonText: newText.target.value })
									}
									value={attributes.buttonText}
								></input>
							</PanelBody>
							<PanelBody title="Messages setting">
								<PanelRow>
									{__("You can edit messages used in various situations")}
								</PanelRow>
								<Button
									className="addFieldButton"
									onClick={() => setShowMessageModel(true)}
								>
									{__("Edit Messages")}
								</Button>
							</PanelBody>
							<PanelBody title="Mail Body settings">
								<PanelRow>
									{__("You can edit the mail template here.")}
								</PanelRow>
								<Button
									className="addFieldButton"
									onClick={() => setShowMailBody(true)}
								>
									{__("Edit Mail Body")}
								</Button>
							</PanelBody>
							<PanelBody title="reCAPTCHA settings">
								<PanelRow>
									{__(
										"reCAPTCHA protects you against spam and other types of automated abuse."
									)}
								</PanelRow>
								<div style={{ marginTop: "10px" }}>
									<CheckboxControl
										label="Add reCAPTCHA"
										value={showReCaptcha}
										onChange={() => setShowReCaptcha(!showReCaptcha)}
									/>
								</div>
							</PanelBody>
						</InspectorControls>
						{showMessageModel && (
							<EditMessages
								props={props}
								setShowMessageModel={setShowMessageModel}
								showMessageModel={showMessageModel}
							/>
						)}
						{showMailBody && (
							<MailBody
								props={props}
								setShowMailBody={setShowMailBody}
								showMailBody={showMailBody}
							/>
						)}
						<DragDropContext onDragEnd={onDragEnd}>
							<Droppable droppableId="droppable">
								{(provided, snapshot) => (
									<div ref={provided.innerRef} {...provided.droppableProps}>
										{attributes?.FormData.map((element, key) => {
											let optionsArray = element.options.split(",");
											return (
												<Draggable
													draggableId={key.toString()}
													index={key}
													key={key}
												>
													{(provided, snapshot) => (
														<div
															ref={provided.innerRef}
															{...provided.draggableProps}
															{...provided.dragHandleProps}
														>
															<div>
																{element.type !== "action" && (
																	<button onClick={() => editClickHandlar(key)}>
																		{__("Edit")}
																	</button>
																)}
																<button onClick={() => removeFieldHandlar(key)}>
																	{__("Delete")}
																</button>
															</div>
															{editModelKey === key && settingModel && (
																<ModalPopup
																	data={element}
																	index={key}
																	props={props}
																	setSettingModel={setSettingModel}
																	settingModel={settingModel}
																/>
															)}
															{element.type == "textarea" ? (
																<div>
																	<p>
																		<label>{element.label}</label>
																	</p>
																	<textarea></textarea>
																</div>
															) : element.type == "action" ? (
																<div>
																	<p>
																		{__(
																			"Your custome action will perform here."
																		)}
																	</p>
																	<RichText
																		{...useBlockProps}
																		tagName="span"
																		value={element.action}
																		style={{ margin: "0px" }}
																		onChange={(action) =>
																			addActionHandlar(action, key)
																		}
																		placeholder={__(
																			"Enter your custome action name."
																		)}
																	/>
																</div>
															) : element.type == "drop-down" ? (
																<div>
																	<p>
																		<label>{element.label}</label>
																	</p>
																	<select multiple={element.multiple}>
																		<option selected disabled>
																			Select Option
																		</option>
																		{optionsArray.map((option, index) => (
																			<option key={index} value={option.value}>
																				{option}
																			</option>
																		))}
																	</select>
																</div>
															) : element.type == "checkboxes" ? (
																optionsArray.length === 1 &&
																optionsArray[1] !== "" ? (
																	<CheckboxControl label="option" />
																) : (
																	optionsArray.map((option) => (
																		<CheckboxControl label={option} />
																	))
																)
															) : element.type == "radio_buttons" ? (
																<div>
																	{optionsArray.length === 1 &&
																	optionsArray[1] !== "" ? (
																		<RadioControl
																			label={element.label}
																			options={[
																				{ label: "redio control", value: "" },
																			]}
																		/>
																	) : (
																		<RadioControl
																			label={element.label}
																			options={optionsArray.map((option) => ({
																				label: option,
																				value: option,
																			}))}
																		/>
																	)}
																</div>
															) : (
																<div>
																	<p>
																		<label>{element.label}</label>
																	</p>
																	<input
																		style={{
																			border: "1px solid black",
																			margin: "0px 0px 20px 0px",
																		}}
																		type={element.type}
																	></input>
																</div>
															)}
														</div>
													)}
												</Draggable>
											);
										})}
									</div>
								)}
							</Droppable>
						</DragDropContext>
					</>
					<button>{attributes.buttonText}</button>
				</div>
			}
		</div>
	);
}
