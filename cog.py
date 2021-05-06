import discord, asyncio
from discord.ext import commands
import json, random, subprocess, string, launcher # Get launcher to utilise its safe_import functionality to ensure the needed installs are present.
from datetime import datetime


class WebHome(commands.Cog):
    def __init__(self, bot):
        self.bot = bot

def setup(bot):

    from plugins.webhome.api import routes
    bot.data["api_routes"].append(["webhome", routes])

    bot.data["webhome"] = {}
    bot.data["webhome"]["valid_users"] = {}
    
    bot.data["webhome"]["custom"] = {}

    bot.data["webhome"]["custom"]["top"] = []
    bot.data["webhome"]["custom"]["bottom"] = []

    bot.add_cog(WebHome(bot))