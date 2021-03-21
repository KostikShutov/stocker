import math
import numpy as np
import pandas as pd
from datetime import date, timedelta
from keras.models import Sequential
from keras.layers import Dense, LSTM, Dropout, Conv1D, MaxPooling1D, Flatten, Activation
from sklearn.preprocessing import MinMaxScaler
from modules.connector import get_data
from modules.formatter import get_result
from modules.parser import get_args

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
model.add(Conv1D(64, 3, input_shape=(x_train.shape[1], 1), padding="same"))
model.add(MaxPooling1D(pool_size=2))
model.add(LSTM(100, return_sequences=True))
model.add(Dropout(0.2))
model.add(Conv1D(32, 3, padding="same"))
model.add(MaxPooling1D(pool_size=2))
model.add(Flatten())
model.add(Dense(units=1))
model.add(Activation('tanh'))
model.compile(loss='mse', optimizer='adam', metrics=['acc', 'mae'])
model.fit(x_train, y_train, epochs=5, batch_size=256, verbose=0)

# Get result
test_data = scaled_data[training_data_len - 60:]
x_test = []
y_test = dataset[training_data_len:, :]

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
print(get_result(data, new_data))
