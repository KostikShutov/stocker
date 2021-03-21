import argparse

parser = argparse.ArgumentParser()

parser.add_argument('--metal', required=True, type=int)
parser.add_argument('--provider', required=True)
parser.add_argument('--start', required=False)
parser.add_argument('--end', required=False)
parser.add_argument('--period', required=True, type=int)

args = parser.parse_args()


def get_args():
    return args.metal, args.provider, args.start, args.end, args.period
