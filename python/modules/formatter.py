import io
import base64
import pandas as pd
import matplotlib.pyplot as plt


def get_result(data, new_data):
    plt.style.use('fivethirtyeight')

    bytes = io.BytesIO()

    plt.figure(figsize=(16, 8))
    plt.title('Model')
    plt.xlabel('Date', fontsize=18)
    plt.ylabel('Close Price USD ($)', fontsize=18)
    plt.plot(data['price'])
    plt.plot(new_data['Predictions'])
    plt.legend(['Train', 'Predictions'], loc='lower right')

    plt.savefig(bytes, format='png', bbox_inches="tight")
    plt.close()

    bytes = base64.b64encode(bytes.getvalue()).decode("utf-8").replace("\n", "")

    new_data.reset_index(level=0, inplace=True)

    return pd.DataFrame({'Image': [bytes], 'Data': [new_data]}).to_json(orient='index')
