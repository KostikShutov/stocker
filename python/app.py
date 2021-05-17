from method_back_propagation import run as run_back_propagation
from method_convolutional_neural_network import run as run_convolutional_neural_network
from method_long_short_term_memory import run as run_long_short_term_memory
from method_radial_basis_function import run as run_radial_basis_function
from method_recurrent_neural_network import run as run_recurrent_neural_network
from flask import Flask

app = Flask(__name__)


@app.route("/method/back_propagation")
def method_back_propagation():
    return run_back_propagation()


@app.route("/method/convolutional_neural_network")
def method_convolutional_neural_network():
    return run_convolutional_neural_network()


@app.route("/method/long_short_term_memory")
def method_long_short_term_memory():
    return run_long_short_term_memory()


@app.route("/method/radial_basis_function")
def method_radial_basis_function():
    return run_radial_basis_function()


@app.route("/method/recurrent_neural_network")
def method_recurrent_neural_network():
    return run_recurrent_neural_network()


if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
