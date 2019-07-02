const { Component } = wp.element;
import PropTypes from 'prop-types';
import axios from 'axios';

class Card extends Component {
	constructor(props) {
		super(props);

		this.state = {
			loading: true,
			cards: [],
		};
	}

	componentDidMount() {
		const { theme } = this.props;
		const DEVELOP_URL_PREFIX = '/_WordPress/wenskaart-app';

		axios
			.get(DEVELOP_URL_PREFIX + `/wp-json/wenskaarten/cards/${theme}`)
			.then(response => {
				this.setState({
					loading: false,
					cards: response.data,
				});
			});
	}

	render() {
		const { cards, loading } = this.state;
		const { handleNextView, handlePreviousView } = this.props;

		return (
			<div className="card-view">
				<h3>Kies een kaart</h3>
				{loading && <p>Laden ...</p>}
				{!loading && (
					<div className="theme-list">
						{cards.map(card => (
							<div key={card.id} className={`card-card card-${card.name}`}>
								<button onClick={() => handleNextView(card.id)}>
									{card.name}
								</button>
							</div>
						))}
					</div>
				)}
				<button onClick={handlePreviousView}>Terug</button>
			</div>
		);
	}
}

Card.propTypes = {
	theme: PropTypes.number.isRequired,
	handleNextView: PropTypes.func.isRequired,
	handlePreviousView: PropTypes.func.isRequired,
};

export default Card;
