import io
import base64
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from flask import request
from sqlalchemy import create_engine


def get_args():
    args = request.args

    return args.get('metal', type=int), \
           args.get('provider', type=str), \
           args.get('start', type=str), \
           args.get('end', type=str), \
           args.get('period', type=int)


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


def get_data(metal, provider, start=None, end=None):
    connection = create_engine('mysql+pymysql://root:root@172.22.0.4/stocker')
    sql = "SELECT date, open_price, high_price, low_price, close_price FROM stocks WHERE metal_id = {0}" \
          " AND provider = '{1}'".format(metal, provider)

    if start is not None:
        sql += " AND date >= '{0}'".format(start)

    if end is not None:
        sql += " AND date <= '{0}'".format(end)

    sql += " ORDER BY date ASC"

    df = pd.read_sql(sql, connection, 'date')  # Получаем данные из бд

    if df.empty:
        quit()

    corr = df.corr().abs()  # Получаем матрицу парных коэффициентов корреляции без отрицательных значений
    corr_matrix = corr.iloc[:, :].values  # Конвертируем Dataframe в квадратную матрицу
    corr_matrix.sort(axis=1)  # Сортируем числа по возрастанию
    corr_matrix = corr_matrix[..., :-1]  # Удаляем последнюю колонку с единицами

    corr_length = len(corr_matrix)  # Количество строк в матрице
    corr_rows = range(corr_length)  # Диапазон строк в матрице
    corr_columns = range(corr_length - 1)  # Диапазон колонок в матрице
    corr_counts = np.zeros(corr_length, dtype=int)

    # Алгоритм нахождения наиболее сильно коллериуемой цены
    for i1 in corr_rows:
        row1 = corr_matrix[i1][::-1]

        for i2 in corr_rows:
            row2 = corr_matrix[i2][::-1]

            if i1 != i2:
                count = 0

                for j in corr_columns:
                    if row1[j] >= row2[j]:
                        count = count + 1
                    else:
                        break

                if count >= 2:
                    corr_counts[i1] = corr_counts[i1] + 1

    corr_index = list(corr.index)[np.argmax(corr_counts)]  # Название наиболее сильно коллериуемой цены

    return df.filter([corr_index]).rename(columns={corr_index: 'price'})  # Фильтруем по найденной цене
