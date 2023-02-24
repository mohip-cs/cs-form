import { registerBlockType } from "@wordpress/blocks";
import "./style.scss";
import Edit from "./edit";
import ListingEdit from "./listingEdit";
registerBlockType("contact-form-cs/contact-form", {
	title: "Contact Form",
	description: "Gutenberg block for contact form.",
	icon: "video-alt3",
	apiVersion: 2,

	supports: {
		multiple: false,
	},

	edit: Edit,
});

registerBlockType("listing-contact-form-cs/listing-contact-form", {
	title: "Listing Contact Form",
	description: "Gutenberg block for contact form.",
	icon: "video-alt3",
	apiVersion: 2,

	supports: {
		multiple: false,
	},

	attributes: {
		postId: {
			type: "string",
			default: "",
		},
	},

	edit: ListingEdit,
});
