import React, { useState } from "react";
import { __ } from "@wordpress/i18n";
import { RadioControl } from "@wordpress/components";
import MailBodyField from "./mailBodyFields";

const mailBody = ({ props, setShowMailBody, showMailBody }) => {
	const { attributes, setAttributes } = props;
	const [showMail2, setShowMail2] = useState(false);

	console.log(attributes.mailBody, "mail_body");

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
				<span>
					<button onClick={() => setShowMailBody(!showMailBody)}>X</button>
				</span>
				<h3 style={{ marginBottom: "0px" }}>Mail</h3>
				<p>You can edit the mail template here.</p>
				<div>
					{attributes.mailBody.map((element, key) => {
						return (
							<MailBodyField
								type="mail"
								element={element}
								key={key}
								props={props}
							/>
						);
					})}
					<RadioControl
						label="Mail (2)"
						options={[{ label: " Use Mail (2)", value: { showMail2 } }]}
						onClick={() => setShowMail2(!showMail2)}
					/>
					<p>
						Mail (2) is an additional mail template often used as an
						autoresponder.
					</p>
					{showMail2 &&
						attributes.mail2Body.map((element, key) => {
							return (
								<MailBodyField
									type="mail"
									element={element}
									key={key}
									props={props}
								/>
							);
						})}
				</div>
			</div>
		</div>
	);
};

export default mailBody;
