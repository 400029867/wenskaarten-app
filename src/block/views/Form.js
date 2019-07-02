import PropTypes from 'prop-types';

const Form = props => {
	return (
		<div className="form-view">
			<h3>Form</h3>
			<p>Card: {props.card}</p>
			<button onClick={() => props.handleSendCard()}>Stuur wenskaart</button>
			<button onClick={props.handlePreviousView}>Terug</button>
		</div>
	);
};

Form.propTypes = {
	card: PropTypes.number.isRequired,
	handlePreviousView: PropTypes.func.isRequired,
	handleSendCard: PropTypes.func.isRequired,
};

export default Form;
