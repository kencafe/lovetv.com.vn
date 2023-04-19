#!/usr/bin/env bash
rm -rf vendor/ && unzip vendor.zip && chown -R hungna:hungna vendor/ && rm -rf vendor.zip && rm -rf __MAC*
rm -rf system/ && unzip system.zip && chown -R hungna:hungna system/ && rm -rf system.zip && rm -rf __MAC*