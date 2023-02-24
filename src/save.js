import { useBlockProps } from '@wordpress/block-editor';

export default function save() {
	return (
		<p { ...useBlockProps.save() }>
			{ 'Contact Form – hello from the saved content!' }
		</p>
	);
}
