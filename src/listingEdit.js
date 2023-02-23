import { useState } from "react";
import { __ } from "@wordpress/i18n";
import { useBlockProps, RichText } from "@wordpress/block-editor";
import "./editor.scss";
import { SelectControl } from "@wordpress/components";
import apiFetch from "@wordpress/api-fetch";

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const [contactFormList, setContactFormList] = useState([]);

	apiFetch({ path: `wp/v2/cs_forms` }).then(
		(result) => {
			setContactFormList(result);
		},
		(error) => {
			console.log(error, "error");
		}
	);
	return (
		<p {...useBlockProps()}>
			{
				<div>
					<select
						onChange={(newValue) => {
							setAttributes({ postId: newValue.target.value });
						}}
						value={attributes.postId}
					>
						<option selected disabled value={0}>
							Select a form
						</option>
						{contactFormList.map((option, index) => (
							<option key={index} value={option.id}>
								{option.title.rendered}
							</option>
						))}
					</select>
				</div>
			}
		</p>
	);
}
