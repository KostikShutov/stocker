import math
import numpy as np
import pandas as pd
from datetime import date, timedelta
from keras.models import Sequential
from keras.layers import Dense, LSTM, Dropout
from sklearn.preprocessing import MinMaxScaler
from common import get_args, is_evaluate, get_evaluate, get_data, get_result


def run():
    # Get data
    metal, provider, start, end, days = get_args()
    data = get_data(metal, provider, start, end)
    dataset = data.values
    training_data_len = math.ceil(len(dataset) * 0.8)
    scaler = MinMaxScaler(feature_range=(0, 1))
    scaled_data = scaler.fit_transform(dataset)
    train_data = scaled_data[0:training_data_len, :]
    x_train = []
    y_train = []

    for i in range(60, len(train_data)):
        x_train.append(train_data[i - 60:i, 0])
        y_train.append(train_data[i, 0])

    x_train, y_train = np.array(x_train), np.array(y_train)
    x_train = np.reshape(x_train, (x_train.shape[0], x_train.shape[1], 1))

    # Build model
    model = Sequential()
    model.add(LSTM(units=500, return_sequences=True, input_shape=(x_train.shape[1], 1)))
    model.add(Dropout(.2))
    model.add(LSTM(units=500, return_sequences=True))
    model.add(Dropout(.2))
    model.add(LSTM(units=500, return_sequences=True))
    model.add(Dropout(.2))
    model.add(LSTM(units=500, return_sequences=False))
    model.add(Dropout(.2))
    model.add(Dense(units=1, activation='sigmoid'))
    model.compile(optimizer='RMSprop', loss='mean_squared_error', metrics=['acc'])
    model.fit(x_train, y_train, epochs=5, batch_size=512, validation_split=0.25)

    if is_evaluate():
        return get_evaluate(model, x_train, y_train)

    # Get result
    test_data = scaled_data[training_data_len - 60:]
    x_test = []

    for i in range(60, len(test_data)):
        x_test.append(test_data[i - 60:i, 0])

    x_test = np.array(x_test)
    x_test = np.reshape(x_test, (x_test.shape[0], x_test.shape[1], 1))
    predictions = np.array([])
    last = x_test[-1]

    for i in range(days):
        curr_prediction = model.predict(np.array([last]))
        last = np.concatenate([last[1:], curr_prediction])
        predictions = np.concatenate([predictions, curr_prediction[0]])

    predictions = scaler.inverse_transform([predictions])[0]
    dicts = []
    curr_date = date.today()

    for i in range(days):
        curr_date = curr_date + timedelta(days=1)
        dicts.append({'Predictions': predictions[i], "Date": curr_date})

    new_data = pd.DataFrame(dicts).set_index("Date")

    return get_result(data, new_data)
