class MyComponent extends React.Component {
	constructor(props) {
		super(props);

		this.state = { toggle: false };
	}

	render() {
		const { toggle } = this.state;

		return (
			<article>
				<h2 className="heading">{this.props.heading}</h2>
				<p className="lead">{this.props.summary}</p>
				<div
					className={toggle}
					onClick={() => {
						this.setState({ toggle: !toggle });
					}}
				>
					{toggle ? "true" : "false"}
				</div>
			</article>
		);
	}
}

export default MyComponent;
