import React from "react";
import { __ } from "@wordpress/i18n";
import { useBlockProps, RichText } from "@wordpress/block-editor";

const editMessages = ({ props, setShowMessageModel, showMessageModel }) => {
	const { attributes, setAttributes } = props;
	console.log(attributes, "edit messages");

	const messageChangeHandlar = (newMessage, key) => {
		const defaultMessage = [...attributes.messages];
		defaultMessage[key].text = newMessage;
		setAttributes({ messages: defaultMessage });
		console.log(newMessage);
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
					maxWidth: "700px",
					width: "100%",
					zIndex: "999",
					height: "50vh",
					overflowY: "auto",
				}}
			>
				<h3 style={{ marginBottom: "0px" }}>Messages</h3>
				<span>
					<button onClick={() => setShowMessageModel(!showMessageModel)}>
						X
					</button>
				</span>
				<div>
					{attributes.messages?.map((message, key) => {
						return (
							<div>
								<label style={{ color: "gray" }}>{__(message.label)}</label>
								<RichText
									{...useBlockProps}
									tagName="p"
									value={message.text}
									style={{ border: "1px solid black", marginTop: "0px" }}
									onChange={(newMessage) =>
										messageChangeHandlar(newMessage, key)
									}
								/>
							</div>
						);
					})}
				</div>
			</div>
		</div>
	);
};

export default editMessages;
