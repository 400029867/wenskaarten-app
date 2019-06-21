import PropTypes from 'prop-types';

const Card = props => {
	return (
		<div className="card-view">
			<h3>Card</h3>
			<p>Theme: {props.theme}</p>
			<button onClick={() => props.handleNextView(4)}>Next</button>
			<button onClick={props.handlePreviousView}>Previous</button>
		</div>
	);
};

Card.propTypes = {
	theme: PropTypes.number.isRequired,
	handleNextView: PropTypes.func.isRequired,
	handlePreviousView: PropTypes.func.isRequired,
};

export default Card;
