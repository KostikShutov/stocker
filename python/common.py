import io
import base64
import argparse
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from flask import request
from sqlalchemy import create_engine


pd.set_option('display.max_rows', None)
pd.set_option('display.max_columns', None)
pd.set_option('display.width', None)
pd.set_option('display.max_colwidth', None)


def get_params_from_request():
    params = request.args

    return params.get('metal', type=int), \
           params.get('provider', type=str, default='yahoo'), \
           params.get('period', type=int), \
           params.get('start', type=str, default=None), \
           params.get('end', type=str, default=None), \
           1 == params.get('evaluate', type=int, default=0)


def get_params_from_args():
    parser = argparse.ArgumentParser()
    parser.add_argument('--metal', type=int, required=True)
    parser.add_argument('--provider', type=str, default='yahoo')
    parser.add_argument('--period', type=int, required=True)
    parser.add_argument('--start', type=str, default=None)
    parser.add_argument('--end', type=str, default=None)
    parser.add_argument('--evaluate', type=int, default=0)
    params = parser.parse_args()

    return params.metal, \
           params.provider, \
           params.period, \
           params.start, \
           params.end, \
           1 == params.evaluate


def get_evaluate(model, x, y):
    metrics = model.evaluate(x, y)

    return pd.DataFrame({'Data': ["Loss: %f, Accuracy %f" % (metrics[0], metrics[1])]})


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

    bytes = base64.b64encode(bytes.getvalue()).decode("utf-8").replace("\n", "")

    new_data.reset_index(level=0, inplace=True)

    return plt, pd.DataFrame({'Image': [bytes], 'Data': [new_data]})


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
