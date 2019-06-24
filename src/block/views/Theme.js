const { Component } = wp.element;
import PropTypes from 'prop-types';
import axios from 'axios';

class Theme extends Component {
	constructor(props) {
		super(props);

		this.themes = [];

		this.state = {
			loading: true,
		};
	}

	componentDidMount() {
		axios.get('https://www.google.com/').then(response => {
			console.log(response.data);
		});
	}

	render() {
		const { themes } = this;
		const { loading } = this.state;
		const { handleNextView } = this.props;

		return (
			<div className="theme-view">
				<h3>Kies een thema</h3>
				{loading && <p>Laden ...</p>}
				{!loading && themes.length > 0 && (
					<div className="theme-list">
						{themes.map(theme => {
							<div key={theme.id} className={`theme-card theme-${theme.name}`}>
								<button onClick={() => handleNextView(theme.id)}>
									{theme.name}
								</button>
							</div>;
						})}
					</div>
				)}
				<button onClick={() => handleNextView(11)}>Volgende</button>
			</div>
		);
	}
}

Theme.propTypes = {
	handleNextView: PropTypes.func.isRequired,
};

export default Theme;
