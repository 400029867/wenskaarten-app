const { Component } = wp.element;
import PropTypes from 'prop-types';
import axios from 'axios';

class Theme extends Component {
	constructor(props) {
		super(props);

		this.state = {
			loading: true,
			themes: [],
		};
	}

	componentDidMount() {
		const DEVELOP_URL_PREFIX = '/_WordPress/wenskaart-app';
		axios
			.get(DEVELOP_URL_PREFIX + '/wp-json/wenskaarten/themes')
			.then(response => {
				this.setState({
					loading: false,
					themes: response.data,
				});
			});
	}

	render() {
		const { loading, themes } = this.state;
		const { handleNextView } = this.props;

		return (
			<div className="theme-view">
				<h3>Kies een thema</h3>
				{loading && <p>Laden ...</p>}
				{!loading && (
					<div className="theme-list">
						{themes.map(theme => (
							<div key={theme.id} className={`theme-card theme-${theme.name}`}>
								<button onClick={() => handleNextView(theme.id)}>
									{theme.name}
								</button>
							</div>
						))}
					</div>
				)}
			</div>
		);
	}
}

Theme.propTypes = {
	handleNextView: PropTypes.func.isRequired,
};

export default Theme;
